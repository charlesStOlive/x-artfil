# Configuration du Formulaire de Contact Livewire

## 📧 Configuration de l'Email

### Variables d'environnement à ajouter dans `.env` :

```env
# Configuration email général
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@votre-site.com
MAIL_FROM_NAME="${APP_NAME}"

# Email de réception pour le formulaire de contact
MAIL_CONTACT_EMAIL=contact@votre-site.com
```

## 🚀 Fonctionnalités Implémentées

### ✅ **Validation des données** :
- Prénom & Nom : Requis, 2-50 caractères
- Email : Requis, format email valide
- Téléphone : Optionnel, format numérique
- Objet : Requis, 5-100 caractères  
- Message : Requis, 10-1000 caractères

### ✅ **Protection anti-spam** :
- **Throttling** : Maximum 3 tentatives par minute par IP
- Auto-nettoyage du throttling en cas de succès
- Messages d'erreur informatifs

### ✅ **Expérience utilisateur** :
- Formulaire réactif avec Livewire
- Messages de succès avec animation
- Compteur de caractères en temps réel
- Indicateur de chargement pendant l'envoi
- Réinitialisation automatique après succès

### ✅ **Design cohérent** :
- Utilisation de la palette bleu/orange
- Classes CSS conformes (primary-*, secondary-*)
- Animations avec `fade-in-*`
- Interface responsive

## 📨 Template Email

Le template email (`resources/views/emails/contact.blade.php`) inclut :
- Design HTML responsive
- Toutes les informations du formulaire
- Formatage professionnel
- Possibilité de répondre directement

## 🛠️ Utilisation

### Dans vos templates Blade :
```blade
<livewire:contact-form />
```

### Personnalisation CSS :
Les styles utilisent les classes de votre palette :
- `text-primary-700` pour les labels
- `border-primary-200` pour les bordures
- `focus:ring-primary-500` pour le focus
- `bg-primary-100` pour les messages de succès

## 🔧 Maintenance

### Logs d'erreurs :
Les erreurs d'envoi sont loggées dans `storage/logs/laravel.log`

### Test en local :
- Utilisez `MAIL_MAILER=log` pour voir les emails dans les logs
- Ou configurez Mailtrap pour tester l'envoi

### Modification des validations :
Éditez les attributs `#[Validate()]` dans `app/Livewire/ContactForm.php`

## 📊 Statistiques disponibles

Pour ajouter des statistiques d'utilisation, vous pouvez :
1. Logger les soumissions réussies
2. Créer une table `contact_submissions`
3. Ajouter des métriques dans le dashboard admin

## 🔒 Sécurité

- Validation côté serveur obligatoire
- Protection CSRF automatique avec Livewire
- Rate limiting par IP
- Sanitisation automatique des données
- Pas de stockage en base par défaut (emails uniquement)

---

**Note** : N'oubliez pas de configurer votre serveur SMTP en production pour l'envoi des emails.