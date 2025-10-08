# Documentation JavaScript - Front App

## 📚 Documentation disponible

Cette section contient la documentation complète du système JavaScript utilisé dans ce projet.

### 🏗️ [Architecture JavaScript](./JAVASCRIPT_ARCHITECTURE.md)

Documentation complète de l'architecture JavaScript :

- Vue d'ensemble du système
- Structure des fichiers
- Pattern Factory Function vs Objet Direct
- Composants Alpine.js intégrés
- API et méthodes disponibles
- Bonnes pratiques de développement

### 🎨 [Système d'Animation](./ANIMATION_SYSTEM.md)

Guide détaillé du système d'animation :

- Principe de fonctionnement (CSS + Intersection Observer)
- Classes d'animation disponibles
- API JavaScript pour le contrôle programmatique
- Optimisations de performance
- Personnalisation et extension
- Exemples d'utilisation complète

## 🚀 Démarrage rapide

### Installation

Le système se charge automatiquement avec Livewire. Aucune installation supplémentaire requise.

### Utilisation de base

```html
<!-- Composant Alpine avec animations -->
<div x-data="frontApp()">
    <!-- Animation automatique -->
    <h1 class="fade-in-up">Titre animé</h1>
    
    <!-- Animation manuelle -->
    <button @click="triggerAnimation('.cards', 'fade-in-left', 100)">
        Animer les cartes
    </button>
    
    <!-- Notification -->
    <button @click="addNotification('Hello!', 'success')">
        Notifier
    </button>
</div>
```

### Composants disponibles

| Composant | Usage | Documentation |
|-----------|-------|---------------|
| `frontApp()` | App principale | [JAVASCRIPT_ARCHITECTURE.md](./JAVASCRIPT_ARCHITECTURE.md#frontapp---méthodes-principales) |
| `modal()` | Modales | [JAVASCRIPT_ARCHITECTURE.md](./JAVASCRIPT_ARCHITECTURE.md#composants-disponibles) |
| `carousel()` | Carrousels | [JAVASCRIPT_ARCHITECTURE.md](./JAVASCRIPT_ARCHITECTURE.md#composants-disponibles) |
| `accordion()` | Accordéons | [JAVASCRIPT_ARCHITECTURE.md](./JAVASCRIPT_ARCHITECTURE.md#composants-disponibles) |

### Classes d'animation

| Classe | Effet | Documentation |
|--------|--------|---------------|
| `.fade-in-up` | Apparition du bas | [ANIMATION_SYSTEM.md](./ANIMATION_SYSTEM.md#animations-dapparition) |
| `.fade-in-left` | Apparition de gauche | [ANIMATION_SYSTEM.md](./ANIMATION_SYSTEM.md#animations-dapparition) |
| `.fade-in-right` | Apparition de droite | [ANIMATION_SYSTEM.md](./ANIMATION_SYSTEM.md#animations-dapparition) |
| `.card-hover` | Effet de survol | [ANIMATION_SYSTEM.md](./ANIMATION_SYSTEM.md#effets-de-survol) |

## 🔧 Développement

### Structure des fichiers

```text
resources/js/front/
├── front.js                    # Point d'entrée principal
├── components/
│   └── animations.js          # Système d'animation
└── docs/
    ├── README.md              # Ce fichier
    ├── JAVASCRIPT_ARCHITECTURE.md
    └── ANIMATION_SYSTEM.md
```

### Debug

```javascript
// Accès aux instances pour debug
console.log(window.frontApp())           // Factory function
console.log(window.frontAppAnimations)   // Animation system  
console.log(window.utils)                // Utilities
```

## 🤝 Contribution

Pour modifier ou étendre le système :

1. **Lisez la documentation** complète avant de commencer
2. **Testez vos modifications** avec différents navigateurs
3. **Mettez à jour la documentation** si nécessaire
4. **Respectez les patterns** existants (Factory Function, Alpine components)

## 📞 Support

Pour toute question sur l'utilisation ou l'extension du système :

1. Consultez d'abord la documentation
2. Vérifiez les exemples d'usage dans les fichiers de doc
3. Testez dans la console du navigateur avec les outils de debug

---

*Cette documentation est maintenue avec le code. Version mise à jour le 8 octobre 2025.*