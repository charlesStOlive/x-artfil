# Générateur de Palettes de Couleurs X-Artfil

## Vue d'ensemble

Cette commande artisan génère automatiquement des palettes de couleurs complètes pour votre projet Laravel/Filament en utilisant la bibliothèque [Iris](https://github.com/ozdemirburak/iris). Elle met à jour automatiquement :

- Les variables CSS dans vos fichiers de thème
- La configuration des couleurs Filament
- Les palettes Tailwind CSS complètes (50-900)

## Installation

La bibliothèque Iris est déjà installée via Composer. La commande est disponible immédiatement.

## Utilisation

### Mode commande avec options

```bash
# Avec couleurs primaire et secondaire spécifiées
php artisan generate:colors --primary=#B24030 --secondary=#F7D463

# Avec couleur tertiaire en plus
php artisan generate:colors --primary=#B24030 --secondary=#F7D463 --tertiary=#10B981

# Avec génération automatique de la couleur secondaire
php artisan generate:colors --primary=#3B82F6 --secondary=auto

# Sans couleur tertiaire (évite la question en mode interactif)
php artisan generate:colors --primary=#8B5CF6 --no-tertiary
```

### Mode interactif

```bash
php artisan generate:colors
```

La commande vous demandera :

1. **Couleur primaire** : Format hex (ex: #B24030)
2. **Couleur secondaire** : Format hex ou Entrée pour génération automatique  
3. **Couleur tertiaire** : Format hex optionnel (Entrée pour ignorer)

## Fonctionnalités

### Génération de palettes

Pour chaque couleur (primaire et secondaire), la commande génère une palette complète :

```css
'primary' => [
    '50'  => '#eff6ff',  /* Très clair */
    '100' => '#dbeafe',
    '200' => '#bfdbfe',
    '300' => '#93c5fd',  /* Clair (utilisé comme primary-light) */
    '400' => '#60a5fa',
    '500' => '#3b82f6',  /* Base (couleur fournie) */
    '600' => '#2563eb',
    '700' => '#1d4ed8',  /* Sombre (utilisé comme primary-dark) */
    '800' => '#1e40af',
    '900' => '#1e3a8a',  /* Très sombre */
]
```

### Variables CSS générées

La commande génère maintenant uniquement les palettes Tailwind complètes (format standard) :

#### Pour tous les fichiers CSS (front.css et filament.css)

```css
/* Palette complète primary (50-900) */
--color-primary-50: #eff6ff;   /* Très clair */
--color-primary-100: #dbeafe;
--color-primary-200: #bfdbfe;
--color-primary-300: #93c5fd;  /* Clair */
--color-primary-400: #60a5fa;
--color-primary-500: #3b82f6;  /* Base (couleur fournie) */
--color-primary-600: #2563eb;
--color-primary-700: #1d4ed8;  /* Sombre */
--color-primary-800: #1e40af;
--color-primary-900: #1e3a8a;  /* Très sombre */

/* Palette complète secondary (50-900) */
--color-secondary-50: #fef8e7;
--color-secondary-100: #fcf2cf;
--color-secondary-200: #fae49e;
--color-secondary-300: #f7d76e;
--color-secondary-400: #f5ca3d;
--color-secondary-500: #f7d463;
--color-secondary-600: #c2970a;
--color-secondary-700: #917108;
--color-secondary-800: #614b05;
--color-secondary-900: #302603;

/* Palette complète tertiary (50-900) - optionnelle */
--color-tertiary-50: #e8fdf6;
--color-tertiary-100: #d0fbed;
--color-tertiary-200: #a1f7da;
--color-tertiary-300: #72f3c8;
--color-tertiary-400: #43efb6;
--color-tertiary-500: #10b981;
--color-tertiary-600: #10bc83;
--color-tertiary-700: #0c8d62;
--color-tertiary-800: #085e41;
--color-tertiary-900: #042f21;
```

### Utilisation des variables

Dans vos CSS, vous pouvez maintenant utiliser directement :

```css
/* Exemples d'utilisation */
.btn-primary {
  background-color: var(--color-primary-500);  /* Couleur de base */
}

.btn-primary:hover {
  background-color: var(--color-primary-600);  /* Plus sombre au survol */
}

.text-primary-light {
  color: var(--color-primary-300);  /* Texte clair */
}

/* Gradients */
.gradient-primary {
  background: linear-gradient(135deg, var(--color-primary-700), var(--color-primary-300));
}
```

### Fichiers mis à jour

1. **resources/css/front/front.css** - Variables CSS pour le frontend
2. **resources/css/filament/admin/filament.css** - Variables CSS pour l'admin Filament  
3. **app/Providers/Filament/AdminPanelProvider.php** - Configuration des couleurs Filament

## Configuration des fichiers

### Balises de détection

Les fichiers CSS doivent contenir les balises suivantes pour que la commande puisse les mettre à jour :

```css
@theme {
  --font-sans: 'Poppins', ui-sans-serif, system-ui, sans-serif;
  /* color-schema-console-generated-start */
  /* Les variables de couleur seront insérées ici */
  /* color-schema-console-generated-end */
}
```

### Structure requise pour Filament Provider

Le fichier `AdminPanelProvider.php` doit contenir une section `colors()` :

```php
->colors([
    'primary' => '#B24030',
    'secondary' => '#F7D463',
])
```

## Couleur secondaire automatique

Quand `--secondary=auto` est utilisé ou que la couleur secondaire est laissée vide, la commande :

1. Calcule la couleur complémentaire sur le cercle chromatique (+180°)
2. Ajuste la saturation et luminosité pour une harmonie optimale
3. Génère une palette complète à partir de cette couleur

## Classes CSS partagées

Les classes utilitaires comme `.prose-brush-primary` et `.prose-brush-secondary` restent dans `shared-utilities.css` et utilisent automatiquement les nouvelles variables CSS.

## Exemple complet

```bash
# Génération avec couleurs spécifiques
php artisan generate:colors --primary=#8B5CF6 --secondary=#10B981

# Résultat affiché :
🎨 Générateur de palettes de couleurs X-Artfil

Couleur primaire: #8B5CF6
Couleur secondaire: #10B981

Génération des palettes de couleurs...
# [Affichage des palettes complètes]

Mise à jour des fichiers CSS...
✅ resources/css/front/front.css
✅ resources/css/filament/admin/filament.css
Mise à jour du provider Filament...
✅ app/Providers/Filament/AdminPanelProvider.php

🎉 Génération terminée ! 2 fichier(s) CSS mis à jour.
N'oubliez pas de recompiler vos assets (npm run build)
```

## Après la génération

N'oubliez pas de recompiler vos assets après avoir utilisé la commande :

```bash
npm run build
# ou
npm run dev
```

## Dépannage

### Fichier non trouvé
- Vérifiez que les fichiers CSS existent
- Vérifiez que les balises `/* color-schema-console-generated-start */` et `/* color-schema-console-generated-end */` sont présentes

### Couleur invalide
- Utilisez le format hex à 6 caractères : `#RRGGBB`
- Exemple valide : `#B24030`, `#3B82F6`
- Exemple invalide : `#B24`, `rgb(178, 64, 48)`

### Provider Filament non mis à jour
- Vérifiez que le fichier `app/Providers/Filament/AdminPanelProvider.php` existe
- Vérifiez qu'il contient une section `->colors([...])` existante