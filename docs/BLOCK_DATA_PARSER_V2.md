# BlockDataParser V2 - Refactorisation Simplifiée

## 🎯 Problème résolu

L'ancienne version du `BlockDataParser` était trop complexe :
- Obligeait d'appeler des méthodes spécifiques pour chaque propriété (`getHtmlFrom()`, `getImageFrom()`, etc.)
- Ne gérait pas bien les sous-tableaux avec `statePath`
- Code répétitif et difficile à maintenir

## 🚀 Nouvelle Approche

### Principe du préfixage automatique

Les champs sont maintenant préfixés selon leur type :
- `html_*` : Contenu HTML (RichEditor) → traité automatiquement
- `image_*` : Images simples → URL générée automatiquement 
- `images_*` : Images multiples → Array d'URLs générées automatiquement
- Autres : Gardés tels quels

### Usage simplifié

#### Avant (V1) :
```php
$parser = \App\Services\BlockDataParser::fromBlockData($data, $mode, $page);
$title = $parser->getHtmlFrom('title');
$description = $parser->getDataFrom('description');
$image = $parser->getImageFrom('background_image');
$photoConfig = $parser->getDataForPhotoFrom('photo_config');
```

#### Après (V2) :
```php
$data = \App\Services\BlockDataParser::fromBlockData($data, $mode, $page);
$title = $data['html_title'];           // Automatiquement traité en HTML
$description = $data['description'];     // Tel quel
$image = $data['image_background'];     // Automatiquement convertie en URL
$photoConfig = $data['photo_config'];   // Sous-tableau traité récursivement
```

## 📝 Configuration des Formulaires

### RichEditor (HTML)
```php
// Le préfixe html_ est ajouté automatiquement
static::getFullRichEditor('title', 'Titre')        // → html_title
static::getTitleRichEditor('subtitle', 'Sous-titre') // → html_subtitle
```

### Images
```php
// Utiliser le préfixe image_ directement
OptimizingFileUpload::make('image_background')   // → URL générée automatiquement
OptimizingFileUpload::make('image_hero')         // → URL générée automatiquement
```

### Sous-tableaux (statePath)
```php
Grid::make(1)
    ->statePath('photo_config')
    ->schema([
        OptimizingFileUpload::make('image_url'), // Traité automatiquement dans le sous-tableau
        Select::make('display_type'),            // Tel quel
        Select::make('position'),                // Tel quel
    ])
```

## 🔧 Migration

### 1. Formulaires PageForm.php
- Renommer `background_image` → `image_background`
- Renommer `url` (dans photo_config) → `image_url`
- Les RichEditor gardent leur nom (préfixe ajouté automatiquement)

### 2. Templates Blade
```php
// Ancien
$parser = \App\Services\BlockDataParser::fromBlockData($data, $mode, $page);
$title = $parser->getHtmlFrom('title');

// Nouveau
$data = \App\Services\BlockDataParser::fromBlockData($data, $mode, $page);
$title = $data['html_title'];
```

### 3. Gestion du mode preview
Le mode preview est géré automatiquement, pas de changement nécessaire.

## ✨ Avantages

1. **Simplicité** : Plus besoin de méthodes spécifiques
2. **Performance** : Traitement en une seule fois
3. **Lisibilité** : Code plus clair et direct
4. **Extensibilité** : Facile d'ajouter de nouveaux types (video_, audio_, etc.)
5. **Compatibilité** : Les statePath fonctionnent naturellement
6. **Maintenance** : Moins de code à maintenir

## 🎨 Exemples complets

### Template Blade complet
```php
@props(['block', 'mode' => 'front', 'page' => null])

@php
    $data = $block['data'] ?? [];
    if (empty($data)) {
        $allVars = get_defined_vars();
        $data = \App\Services\BlockDataParser::extractDataFromBladeVars($allVars);
    } else {
        $data = \App\Services\BlockDataParser::fromBlockData($data, $mode, $page);
    }

    // Accès direct, simple et clair
    $title = $data['html_title'] ?? null;
    $image = $data['image_hero'] ?? null;
    $photoConfig = $data['photo_config'] ?? [];
@endphp

<section>
    @if($title)
        <h2>{!! $title !!}</h2>
    @endif
    
    @if($image)
        <img src="{{ $image }}" alt="Hero">
    @endif
    
    @if($photoConfig['image_url'] ?? null)
        <x-photo :data="$photoConfig" />
    @endif
</section>
```

Cette approche rend le code beaucoup plus maintenable et intuitif !