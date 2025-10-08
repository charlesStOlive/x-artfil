# Configuration des Paramètres Administrateur

## Installation et Configuration

La librairie **Filament Spatie Settings** a été installée et configurée pour gérer les paramètres administrateur du site.

### Classes et Fichiers créés

1. **`App\Settings\AdminSettings`** - Classe de paramètres avec les champs :
   - `email` : Email principal de l'organisation
   - `telephone` : Numéro de téléphone
   - `adresse` : Adresse physique
   - `horaire` : Horaires d'ouverture
   - `mailRecepteur` : Email qui recevra les messages du formulaire de contact
   - `logo` : Logo de l'organisation (fichier image)

2. **`App\Filament\Pages\AdminSettingsPage`** - Page Filament pour gérer les paramètres
   - Accessible via le menu Filament admin
   - Interface intuitive avec sections organisées
   - Upload de fichier pour le logo

3. **`app\helpers.php`** - Fonctions helpers pour accéder aux paramètres :
   - `admin_settings()` : Retourne l'instance des paramètres
   - `admin_setting($key, $default)` : Récupère une valeur spécifique

## Utilisation dans les vues

### Fonction helper recommandée
```blade
{{ admin_setting('email', 'default@example.com') }}
{{ admin_setting('telephone', '+33 1 23 45 67 89') }}
{!! nl2br(e(admin_setting('adresse', 'Adresse par défaut'))) !!}
{!! nl2br(e(admin_setting('horaire', 'Horaires par défaut'))) !!}
```

### Accès direct à l'instance
```blade
@php
$adminSettings = admin_settings();
@endphp

<p>{{ $adminSettings->email }}</p>
<p>{{ $adminSettings->telephone }}</p>
```

## Utilisation dans les contrôleurs/services

```php
use App\Settings\AdminSettings;

class ContactController
{
    public function sendEmail(AdminSettings $settings)
    {
        $recipientEmail = $settings->mailRecepteur;
        // ... logique d'envoi
    }
}
```

## Accès à l'interface administrateur

1. Connectez-vous à l'interface Filament admin
2. Naviguez vers "Paramètres Admin" dans le menu
3. Modifiez les valeurs selon vos besoins
4. Sauvegardez les modifications

Les changements sont immédiatement reflétés sur le site frontend.

## Implémentation actuelle

Les paramètres sont actuellement utilisés dans :
- **Page de contact** (`resources/views/components/blocks/contact.blade.php`)
- **Formulaire de contact Livewire** (`app/Livewire/ContactForm.php`) pour l'email récepteur

## Extensibilité

Pour ajouter de nouveaux paramètres :
1. Ajoutez les propriétés dans `AdminSettings`
2. Créez une nouvelle migration settings
3. Mettez à jour le formulaire Filament
4. Exécutez `php artisan settings:discover`