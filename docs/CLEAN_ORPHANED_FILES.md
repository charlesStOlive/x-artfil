# Commande de Nettoyage des Fichiers Orphelins

## Vue d'ensemble

La commande `files:clean-orphaned` analyse les modèles configurés dans votre application Laravel et nettoie les fichiers qui ne sont plus référencés dans la base de données.

## Utilisation

```bash
# Mode test - affiche ce qui serait supprimé sans rien supprimer
php artisan files:clean-orphaned --dry-run

# Mode détaillé pour voir les détails de l'analyse
php artisan files:clean-orphaned --detailed --dry-run

# Nettoyage réel avec confirmation
php artisan files:clean-orphaned

# Nettoyage forcé sans confirmation (attention !)
php artisan files:clean-orphaned --force
```

## Options disponibles

- `--dry-run` : Mode test qui affiche les actions sans les exécuter
- `--detailed` : Affiche des informations détaillées sur l'analyse
- `--force` : Évite la demande de confirmation (utiliser avec précaution)

## Configuration des modèles

La configuration se trouve dans la méthode `getModelsConfiguration()` de la commande. Voici la structure actuelle :

### Modèles configurés

#### 1. Pages (App\Models\Page)
- **Champs analysés** : `contents` (structure JSON des blocs)
- **Répertoires nettoyés** : `images-bg`, `image-photos`
- **Méthode d'extraction** : `extractFromPageContents`

#### 2. AdminSettings (App\Settings\AdminSettings)
- **Champs analysés** : `logo`
- **Répertoires nettoyés** : `logos`
- **Méthode d'extraction** : `extractFromSettings`

## Ajouter un nouveau modèle

Pour ajouter un nouveau modèle à analyser, suivez ces étapes :

### 1. Modifier la configuration

Dans `getModelsConfiguration()`, ajoutez une nouvelle entrée :

```php
[
    'name' => 'MonNouveauModele',
    'model' => App\Models\MonNouveauModele::class,
    'fields' => ['champ_avec_fichiers', 'autre_champ'], 
    'directories' => ['mon-repertoire', 'autre-repertoire'],
    'extractor' => 'extractFromMonNouveauModele' // Nom de la méthode à créer
]
```

### 2. Créer la méthode d'extraction

Ajoutez une méthode dans la classe pour extraire les chemins de fichiers :

```php
protected function extractFromMonNouveauModele(array $config): \Illuminate\Support\Collection
{
    $model = $config['model'];
    $usedFiles = collect();

    $model::all()->each(function ($record) use (&$usedFiles, $config) {
        foreach ($config['fields'] as $field) {
            $value = $record->$field;
            
            // Si c'est un array/JSON, extraire récursivement
            if (is_array($value)) {
                $this->extractFilesFromArray($value, $usedFiles);
            }
            // Si c'est une chaîne simple
            elseif (is_string($value) && $this->looksLikeFilePath($value)) {
                $usedFiles->push($value);
            }
        }
    });

    return $usedFiles->filter()->unique();
}
```

### 3. Mettre à jour la détection de chemins (optionnel)

Si vous utilisez de nouveaux répertoires, mettez à jour la méthode `looksLikeFilePath()` :

```php
protected function looksLikeFilePath(string $value): bool
{
    $knownDirectories = [
        'images-bg/', 
        'image-photos/', 
        'logos/',
        'mon-nouveau-repertoire/' // Ajouter ici
    ];
    
    // ... reste de la méthode
}
```

## Exemples d'usage avancé

### Modèle avec relations

```php
protected function extractFromModeleAvecRelations(array $config): \Illuminate\Support\Collection
{
    $model = $config['model'];
    $usedFiles = collect();

    $model::with('relation')->get()->each(function ($record) use (&$usedFiles) {
        // Fichiers du modèle principal
        if ($record->image) {
            $usedFiles->push($record->image);
        }
        
        // Fichiers des relations
        $record->relation->each(function ($related) use (&$usedFiles) {
            if ($related->photo) {
                $usedFiles->push($related->photo);
            }
        });
    });

    return $usedFiles->filter()->unique();
}
```

### Modèle avec structure JSON complexe

```php
protected function extractFromModeleJSON(array $config): \Illuminate\Support\Collection
{
    $model = $config['model'];
    $usedFiles = collect();

    $model::all()->each(function ($record) use (&$usedFiles) {
        $data = json_decode($record->json_field, true);
        
        if (is_array($data)) {
            // Utilise la méthode helper existante
            $this->extractFilesFromArray($data, $usedFiles);
        }
    });

    return $usedFiles->filter()->unique();
}
```

## Sécurité et bonnes pratiques

1. **Toujours tester en mode dry-run** avant le nettoyage réel
2. **Sauvegarder** vos fichiers avant un nettoyage important
3. **Vérifier la configuration** des nouveaux modèles
4. **Tester avec des données de développement** avant la production

## Fonctionnalités

- ✅ Analyse multiple de modèles configurables
- ✅ Extraction récursive depuis les structures JSON/array
- ✅ Nettoyage sécurisé avec confirmation
- ✅ Mode test (dry-run) sans suppression
- ✅ Suppression des dossiers vides
- ✅ Rapports détaillés des opérations
- ✅ Support des relations et structures complexes