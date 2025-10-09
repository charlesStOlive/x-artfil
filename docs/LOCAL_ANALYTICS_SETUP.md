# Système d'Analytics Local Laravel

## 📊 Vue d'ensemble

Ce système d'analytics **100% local** remplace Google Analytics et stocke toutes les données dans votre base de données Laravel. Aucune donnée n'est envoyée à des services tiers.

## 🚀 Fonctionnalités

### ✅ Tracking automatique
- **Middleware intelligent** : Suit automatiquement toutes les pages visitées
- **Filtrage automatique** : Ignore les requêtes AJAX, admin, et API
- **Détection d'appareils** : Mobile, tablette, desktop
- **Détection de navigateur et OS**
- **Géolocalisation par IP**
- **Référents et sources de trafic**

### 📈 Dashboard Analytics
- **Widget de statistiques** avec métriques en temps réel :
  - Visites aujourd'hui vs hier
  - Visiteurs uniques
  - Visites de la semaine
  - Page la plus populaire

- **Graphiques interactifs** :
  - Évolution des visites (7 derniers jours)
  - Répartition par appareil
  - Tendances temporelles

### 🔍 Interface d'administration
- **Liste des visites** avec filtres avancés :
  - Par date (aujourd'hui, hier, semaine, mois)
  - Par type d'appareil
  - Par plateforme/OS
- **Recherche** par IP, page, référent
- **Export des données**
- **Vue détaillée** de chaque visite

## 🛠 Installation et Configuration

### 1. Package utilisé
```bash
composer require shetabit/visitor
```

### 2. Middleware de tracking
Le middleware `TrackVisitor` est automatiquement ajouté et suit :
- ✅ Pages GET avec statut 200
- ❌ Requêtes AJAX/JSON
- ❌ Pages d'administration
- ❌ Routes internes Laravel

### 3. Base de données
Table `visits` avec les colonnes :
- `ip` - Adresse IP du visiteur
- `method` - Méthode HTTP
- `request` - URL demandée
- `url` - URL complète
- `referer` - Page de provenance
- `languages` - Langues acceptées
- `useragent` - User Agent complet
- `device` - Type d'appareil (mobile/desktop/tablet)
- `platform` - Système d'exploitation
- `browser` - Navigateur utilisé
- `created_at` - Date/heure de la visite

## 📊 Service Analytics

Le `AnalyticsService` fournit des méthodes pour :

### Statistiques générales
```php
$analytics = new AnalyticsService();
$stats = $analytics->getGeneralStats();
// Retourne : total_visits, unique_visitors, today_visits, etc.
```

### Pages populaires
```php
$topPages = $analytics->getTopPages(10);
// Les 10 pages les plus visitées
```

### Statistiques par appareil
```php
$devices = $analytics->getDeviceStats();
// Répartition mobile/desktop/tablet
```

### Tendances temporelles
```php
$dailyVisits = $analytics->getDailyVisits(30);
// Visites des 30 derniers jours

$hourlyVisits = $analytics->getHourlyVisits();
// Visites par heure aujourd'hui
```

### Croissance
```php
$growth = $analytics->getGrowthStats();
// Croissance hebdomadaire et mensuelle
```

## 🎯 Utilisation avancée

### 1. Tracking personnalisé
```php
use Shetabit\Visitor\Facades\Visitor;

// Dans un contrôleur ou service
Visitor::visit($model); // Associer à un modèle
Visitor::visitor($ip)->visit(); // IP spécifique
```

### 2. Filtres et segmentation
```php
// Visites d'une page spécifique
Visit::where('request', '/contact')->count();

// Visiteurs mobiles uniquement
Visit::where('device', 'mobile')->count();

// Visites depuis Google
Visit::where('referer', 'like', '%google.com%')->count();
```

### 3. Rapports personnalisés
```php
// Taux de conversion approximatif
$totalVisits = Visit::count();
$contactVisits = Visit::where('request', '/contact')->count();
$conversionRate = ($contactVisits / $totalVisits) * 100;
```

## 📈 Métriques disponibles

### Métriques de base
- **Visites totales** - Nombre total de pages vues
- **Visiteurs uniques** - Nombre d'IPs distinctes
- **Pages vues** - Pages les plus consultées
- **Durée de session** - Estimation basée sur les visites consécutives
- **Taux de rebond** - Visiteurs avec une seule page vue

### Métriques techniques
- **Navigateurs** - Chrome, Firefox, Safari, etc.
- **Systèmes d'exploitation** - Windows, macOS, Linux, etc.
- **Appareils** - Mobile, Desktop, Tablette
- **Résolutions d'écran** - Via User Agent

### Métriques de sources
- **Trafic direct** - Visiteurs sans référent
- **Moteurs de recherche** - Google, Bing, etc.
- **Réseaux sociaux** - Facebook, Twitter, etc.
- **Sites référents** - Autres sites web

## 🔧 Configuration avancée

### 1. Personaliser le tracking
Éditer `config/visitor.php` :
```php
return [
    'default_driver' => 'eloquent',
    'connection_name' => null,
    'table' => 'visits',
];
```

### 2. Exclure des pages
Modifier le middleware `TrackVisitor` :
```php
// Ajouter des exclusions
if ($request->is('admin*') || 
    $request->is('api*') ||
    $request->is('sitemap.xml')) {
    return $response;
}
```

### 3. Optimisation des performances
```php
// Dans AppServiceProvider
public function boot()
{
    // Nettoyer les anciennes visites (cron recommandé)
    if (app()->runningInConsole()) {
        Visit::where('created_at', '<', now()->subMonths(6))->delete();
    }
}
```

## 🚨 Bonnes pratiques

### 1. Performances
- **Nettoyage régulier** : Supprimer les visites > 6-12 mois
- **Index de base de données** sur `created_at`, `ip`, `request`
- **Cache des statistiques** pour les widgets

### 2. Confidentialité
- **Anonymisation des IPs** après X jours
- **Respect du RGPD** - données stockées localement
- **Opt-out possible** via cookie ou paramètre

### 3. Monitoring
- **Logs des erreurs** de tracking
- **Alertes** sur les pics de trafic
- **Rapports automatiques** hebdomadaires/mensuels

## 🔗 Intégration Filament

### Widgets disponibles
1. **AnalyticsStatsWidget** - Métriques clés
2. **AnalyticsChartWidget** - Graphique des tendances
3. **VisitResource** - Gestion des visites

### Pages admin
- `/admin/visits` - Liste des visites
- `/admin/visits/{id}` - Détail d'une visite

## ⚡ Performance et scalabilité

Ce système peut gérer :
- **Petits sites** : < 1000 visites/jour → Performance excellente
- **Sites moyens** : < 10000 visites/jour → Performance très bonne
- **Gros sites** : > 50000 visites/jour → Optimisations requises

### Optimisations recommandées
1. **Index sur `created_at` et `ip`**
2. **Partitioning par mois/année**
3. **Archivage des anciennes données**
4. **Cache Redis pour les métriques**
5. **Queue jobs pour le traitement**

---

## 🎉 Résultat

Vous avez maintenant un **système d'analytics complet et local** qui :
- ✅ Remplace Google Analytics
- ✅ Respecte la vie privée
- ✅ Stocke tout localement
- ✅ S'intègre parfaitement à Filament
- ✅ Fournit des métriques détaillées
- ✅ Offre des graphiques interactifs

**Accès admin** : `/admin/visits` pour voir toutes les statistiques !