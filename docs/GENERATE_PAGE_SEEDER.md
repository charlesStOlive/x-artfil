# Commande de Génération de Seeder pour Pages

## Vue d'ensemble

La commande `seeder:pages` génère automatiquement un seeder Laravel pour toutes les pages de votre base de données, avec la possibilité d'extraire et copier les images associées.

## Utilisation

```bash
# Génération simple d'un seeder
php artisan seeder:pages

# Génération avec nom personnalisé
php artisan seeder:pages MonSeederPersonalise

# Génération sans images (exclusion)
php artisan seeder:pages --no-images

# Génération avec chemin de sortie personnalisé
php artisan seeder:pages --output-path=/chemin/personnalise

# Génération complète avec toutes les options
php artisan seeder:pages MonSeeder --no-images --output-path=/mon/chemin
```

## Options disponibles

- `name` : Nom du seeder (défaut: PagesSeeder)
- `--no-images` : Ignore la copie des images (par défaut les images sont copiées)
- `--output-path` : Chemin personnalisé pour le fichier seeder (défaut: database/seeders)

## Fonctionnalités

### 🔍 Extraction automatique des données
- Récupère toutes les pages de la table `cms_pages`
- Exporte tous les champs y compris les structures JSON complexes
- Préserve les relations et métadonnées

### 🖼️ Gestion des images (par défaut, sauf avec `--no-images`)
- Détecte automatiquement les images dans les champs JSON
- Copie les images vers `database/seeders/images/{nom_seeder}/`
- Génère un mapping pour restaurer les images au bon endroit
- Supporte les répertoires : `images-bg/` et `image-photos/`
- Extensions supportées : jpg, jpeg, png, gif, webp, svg

### 📝 Génération du seeder
- Crée un seeder Laravel standard
- Inclut une méthode pour restaurer les images
- Utilise DB::table() pour éviter les problèmes de modèles
- Gère les timestamps automatiquement

## Structure générée

### Fichier seeder
```
database/seeders/MonSeeder.php
```

### Images (si --with-images)
```
database/seeders/images/mon_seeder/
├── image1.webp
├── image2.jpg
└── image3.png
```

## Fonctionnement du seeder généré

Le seeder généré contient deux méthodes principales :

### 1. `copyImages()`
- Copie les images du répertoire seeder vers `storage/app/public`
- Utilise le mapping pour restaurer les chemins d'origine
- Crée les répertoires manquants automatiquement

### 2. `seedPages()`
- Insère toutes les données de pages
- Préserve la structure JSON des contenus
- Génère de nouveaux IDs (auto-increment)

## Exemple d'utilisation complète

```bash
# 1. Générer le seeder (avec images par défaut)
php artisan seeder:pages ProductionPages

# 2. Le seeder sera créé avec :
#    - database/seeders/ProductionPages.php
#    - database/seeders/images/production_pages/[images]

# 3. Pour restaurer sur un autre environnement :
php artisan db:seed --class=ProductionPages
```

## Cas d'usage recommandés

### 🚀 Migration d'environnements
```bash
# Environnement de production
php artisan seeder:pages ProductionBackup --with-images

# Copier les fichiers vers le nouvel environnement
# Puis sur le nouvel environnement :
php artisan db:seed --class=ProductionBackup
```

### 🧪 Tests et développement
```bash
# Créer un jeu de données de test
php artisan seeder:pages TestData

# Utiliser dans les tests ou pour réinitialiser
php artisan migrate:fresh --seed --seeder=TestData
```

### 📦 Packages et distribution
```bash
# Créer un seeder pour distribution
php artisan seeder:pages DemoContent --output-path=packages/demo/database/seeders
```

## Configuration avancée

### Personnaliser les répertoires d'images
Pour supporter d'autres répertoires d'images, modifiez la méthode `looksLikeImagePath()` dans `GeneratePageSeederCommand.php` :

```php
protected function looksLikeImagePath(string $value): bool
{
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    $extension = pathinfo($value, PATHINFO_EXTENSION);
    
    return in_array(strtolower($extension), $imageExtensions) 
        && (str_contains($value, 'images-bg/') 
         || str_contains($value, 'image-photos/')
         || str_contains($value, 'mon-nouveau-repertoire/')); // Ajouter ici
}
```

### Exclure certains champs
Modifiez la méthode `preparePagesData()` pour exclure des champs sensibles :

```php
// Dans preparePagesData()
unset($data['id']);
unset($data['champ_sensible']); // Ajouter ici
```

## Sécurité et bonnes pratiques

1. **Vérifiez les données** avant de générer un seeder en production
2. **Testez le seeder** sur un environnement de développement
3. **Sauvegardez** avant d'exécuter un seeder sur des données importantes
4. **Vérifiez les permissions** sur les répertoires d'images
5. **Nettoyez les données sensibles** si nécessaire

## Dépannage

### Images manquantes
- Vérifiez que les images existent dans `storage/app/public`
- Contrôlez les permissions des répertoires
- Utilisez l'option verbose pour voir les détails : `php artisan seeder:pages --with-images -v`

### Erreurs de seeder
- Vérifiez la syntaxe du fichier généré
- Contrôlez les contraintes de base de données (clés uniques, etc.)
- Testez d'abord avec `--dry-run` si disponible

### Problèmes de chemins
- Utilisez des chemins absolus avec `--output-path`
- Vérifiez que les répertoires de destination existent et sont accessibles en écriture