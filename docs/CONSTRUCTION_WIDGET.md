# Widget Mode Construction - Documentation

## Vue d'ensemble

Le widget **ConstructionModeWidget** fournit une interface simple dans le tableau de bord Filament pour gérer le mode construction du site.

## Fonctionnalités

### 1. Affichage du statut
- **Icône visuelle** : ✅ Vert si désactivé, ⚠️ Orange si activé
- **Statut textuel** : "Activé" ou "Désactivé" avec titre personnalisé
- **Information contextuelle** : Description de l'état actuel

### 2. Actions rapides
- **Bouton Toggle** : Activation/désactivation en un clic
- **Bouton Paramètres** : Accès direct à la page de configuration
- **Notifications** : Confirmation des actions via toast

### 3. Avertissements
- **Bandeau d'alerte** : Affiché quand le mode construction est activé
- **Message d'information** : Rappel que seuls les admins voient le site normal

## Interface utilisateur

### État désactivé
```
✅ Mode Construction
   Désactivé - Site accessible à tous
   
   [Activer]  [Paramètres]
```

### État activé
```
⚠️ Mode Construction
   Activé - Site en maintenance
   
   [Désactiver]  [Paramètres]
   
   ⚠️ Attention : Le site affiche la page de maintenance
      Seuls les administrateurs connectés peuvent voir le site normal.
```

## Configuration technique

### Position dans le dashboard
- **Priorité** : `sort = 1` (affiché en premier)
- **Largeur** : `columnSpan = 'full'` (pleine largeur)
- **Widgets remplacés** : `FilamentInfoWidget` supprimé

### Réactivité
- **État local** : `$isActive` pour l'affichage immédiat
- **Synchronisation** : Mise à jour automatique après toggle
- **Notifications** : Feedback utilisateur via Filament Notifications

### Sécurité
- **Accès** : Réservé aux utilisateurs authentifiés sur le panel admin
- **Actions** : Modification des settings via `AdminSettings`
- **Validation** : Vérification des permissions Filament

## Intégration

### Dans AdminPanelProvider
```php
->widgets([
    \App\Filament\Widgets\ConstructionModeWidget::class,
    AccountWidget::class,
])
```

### Routes utilisées
- **Page paramètres** : `filament.admin.pages.admin-settings-page`
- **Dashboard** : Affiché automatiquement sur `/admin`

## Avantages

- ✅ **Accès rapide** : Toggle en un clic depuis le dashboard
- ✅ **Visuel clair** : État immédiatement visible
- ✅ **Navigation fluide** : Lien direct vers les paramètres
- ✅ **Feedback utilisateur** : Notifications des actions
- ✅ **Responsive** : Interface adaptée à Filament
- ✅ **Sécurisé** : Intégré dans le système d'auth Filament

## Maintenance

Le widget se met à jour automatiquement avec les changements dans `AdminSettings`. Aucune maintenance spécifique requise.