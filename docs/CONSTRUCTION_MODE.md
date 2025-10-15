# Mode Construction - Documentation

## Vue d'ensemble

Le système de mode construction permet d'afficher une page de maintenance personnalisée lorsque le site est en cours de mise à jour, tout en permettant aux administrateurs connectés de continuer à naviguer normalement.

## Configuration

### 1. Settings disponibles

Les paramètres sont stockés dans `AdminSettings` :

- `footerText` : Texte affiché dans le footer
- `construction.activate` : Active/désactive le mode construction
- `construction.titre` : Titre affiché sur la page de maintenance
- `construction.description` : Description affichée sur la page de maintenance

### 2. Helpers disponibles

#### `admin_setting($key, $default = null)`
Récupère un paramètre avec support de la notation dot :
```php
admin_setting('footerText')
admin_setting('construction.activate')
admin_setting('construction.titre')
```

#### Accès direct aux settings
Pour les paramètres de construction, utilisez directement la classe :
```php
$settings = app(\App\Settings\AdminSettings::class);
$isActive = $settings->construction['activate'] ?? false;
$title = $settings->construction['titre'] ?? 'Site en maintenance';
```

### 3. Administration

Utilisez la page **Paramètres Admin** dans Filament pour :
- Activer/désactiver le mode maintenance
- Personnaliser le titre et la description
- Configurer les autres paramètres du site

## Fonctionnement technique

### Trait HandlesConstructionMode

Le trait `App\Livewire\Concerns\HandlesConstructionMode` gère la redirection vers la page de construction :

```php
use App\Livewire\Concerns\HandlesConstructionMode;

class MonComposant extends Component
{
    use HandlesConstructionMode;

    public function mount()
    {
        $this->checkConstruction();
        // ... reste du code mount
    }
}
```

### Méthode du trait

- `checkConstruction()` : Vérifie et redirige si le mode construction est activé

### Logic de fonctionnement

1. **Vérification** dans `mount()` : Construction activée ET utilisateur non-admin
2. **Si oui** → Redirection vers `/construction`
3. **Sinon** → Composant continue normalement

### Pages et routes

- **Route construction** : `/construction` → `ConstructionPage`
- **Layout construction** : `layouts.construction` (minimal, centré)
- **Vue construction** : `livewire.front.construction-page`
- **Contenu** : Logo, titre, description, contact

## Intégration dans nouveaux composants

Pour ajouter le support du mode construction à un nouveau composant Livewire :

1. Ajouter le trait :
```php
use App\Livewire\Concerns\HandlesConstructionMode;

class NouveauComposant extends Component
{
    use HandlesConstructionMode;

    public function mount()
    {
        $this->checkConstruction();
        // ... reste du code
    }
}
```

C'est tout ! Le composant redirigera automatiquement vers `/construction` si nécessaire.

## Avantages de cette approche

- ✅ **Ultra simple** : Accès direct aux settings, pas de helpers inutiles
- ✅ **Flexible** : Contrôle au niveau du composant
- ✅ **DRY** : Code réutilisable via le trait
- ✅ **Performant** : Vérification uniquement quand nécessaire  
- ✅ **Sécurisé** : Les admins connectés ne voient pas la maintenance
- ✅ **Personnalisable** : Interface d'administration intuitive
- ✅ **Direct** : Plus de couches d'abstraction inutiles