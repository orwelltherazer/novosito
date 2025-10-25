# NovoSito CMS

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

**NovoSito** est un CMS modulaire léger et performant conçu pour créer rapidement des sites vitrines simples et fonctionnels. Parfait pour les auto-entrepreneurs, artisans, associations ou particuliers.

🔗 **GitHub** : [https://github.com/orwelltherazer/novosito](https://github.com/orwelltherazer/novosito)

## Caractéristiques principales

- **Interface d'administration intuitive** : Back-office moderne et facile à utiliser
- **Éditeur de contenu visuel** : Système de modules drag & drop
- **Architecture modulaire** : Ajoutez ou retirez facilement des blocs de contenu
- **Modules inclus** : Texte, Image, Galerie, Formulaire de contact, Carte géographique
- **Thèmes personnalisables** : Système de templates flexible
- **Responsive design** : Compatible mobile, tablette et desktop
- **SEO-friendly** : Optimisation pour les moteurs de recherche
- **Léger et rapide** : Architecture MVC optimisée

## Modules disponibles

### Modules de contenu
- **Texte** : Éditeur WYSIWYG pour le contenu textuel
- **Image** : Affichage d'images avec légendes et liens
- **Galerie** : Galeries d'images personnalisables
- **HTML** : Code HTML personnalisé
- **Vidéo** : Intégration YouTube/Vimeo

### Modules interactifs
- **Formulaire de contact** : Formulaires personnalisables
- **Carte** : Intégration de cartes géographiques (OpenStreetMap)

## Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou MariaDB 10.3 ou supérieur
- Apache avec mod_rewrite activé
- Extension PHP : PDO, PDO_MySQL

## Installation

### 1. Téléchargement

Clonez ou téléchargez le projet dans votre répertoire web :

```bash
cd /chemin/vers/votre/serveur/web
git clone https://github.com/votre-username/novosito.git
```

### 2. Configuration de la base de données

Éditez le fichier de configuration de la base de données :

```bash
nano config/database.php
```

Modifiez les paramètres de connexion :

```php
return [
    'host' => 'localhost',
    'database' => 'novosito_cms',
    'username' => 'votre_utilisateur',
    'password' => 'votre_mot_de_passe',
    'charset' => 'utf8mb4',
];
```

### 3. Installation de la base de données

Exécutez le script d'installation pour créer les tables :

```bash
php database/install.php
```

Ou importez manuellement le fichier SQL :

```bash
mysql -u votre_utilisateur -p novosito_cms < database/schema.sql
```

### 4. Configuration du serveur web

#### Apache

Assurez-vous que le fichier `.htaccess` est présent à la racine et que `mod_rewrite` est activé.

Pour activer mod_rewrite sur Ubuntu/Debian :

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Configuration des permissions

```bash
chmod -R 755 uploads/
chmod -R 755 themes/
```

### 5. Connexion à l'administration

Accédez au back-office :

```
http://votre-domaine.com/admin
```

**Identifiants par défaut** :
- Email : `admin@example.com`
- Mot de passe : `admin123`

**⚠️ IMPORTANT : Changez ces identifiants immédiatement après votre première connexion !**

## Structure du projet

```
novosito/
├── admin/                  # Back-office d'administration
│   ├── assets/            # CSS/JS de l'admin
│   ├── controllers/       # Contrôleurs admin
│   └── views/             # Vues de l'admin
├── api/                   # Points d'accès API REST
├── app/                   # Application
│   ├── Controllers/       # Contrôleurs
│   └── Models/            # Modèles
├── assets/                # Ressources publiques
│   ├── css/
│   ├── js/
│   └── img/
├── config/                # Configuration
│   ├── app.php           # Configuration générale
│   ├── database.php      # Configuration BDD
│   └── modules.php       # Configuration des modules
├── core/                  # Cœur du CMS
│   ├── Autoloader.php    # Chargement automatique
│   ├── Controller.php    # Contrôleur de base
│   ├── Database.php      # Gestion BDD
│   ├── Model.php         # Modèle de base
│   ├── Module.php        # Système de modules
│   ├── Router.php        # Routage
│   └── View.php          # Gestion des vues
├── database/              # Base de données
│   ├── schema.sql        # Structure de la BDD
│   └── install.php       # Script d'installation
├── modules/               # Modules disponibles
│   ├── text/             # Module texte
│   ├── image/            # Module image
│   ├── gallery/          # Module galerie
│   ├── contact/          # Module contact
│   ├── map/              # Module carte
│   ├── video/            # Module vidéo
│   └── html/             # Module HTML
├── themes/                # Thèmes
│   └── default/          # Thème par défaut
├── uploads/               # Fichiers uploadés
├── .htaccess             # Configuration Apache
├── index.php             # Point d'entrée
└── README.md             # Documentation
```

## Utilisation

### Créer une nouvelle page

1. Connectez-vous au back-office (`/admin`)
2. Cliquez sur "Pages" dans le menu
3. Cliquez sur "Nouvelle page"
4. Remplissez les informations :
   - Titre de la page
   - Titre SEO (optionnel)
   - Description SEO (optionnel)
   - Statut (Brouillon/Publié)
5. Cliquez sur "Créer la page"

### Ajouter des modules à une page

1. Éditez une page existante
2. Dans la section "Modules de contenu", cliquez sur "Ajouter un module"
3. Choisissez le type de module souhaité
4. Configurez le module selon vos besoins
5. Réorganisez les modules par glisser-déposer
6. Sauvegardez

### Gérer les médias

1. Accédez à "Médias" dans le back-office
2. Uploadez vos images et fichiers
3. Utilisez-les dans vos modules

## Créer un nouveau module

Pour créer un module personnalisé :

### 1. Créer la structure

```bash
mkdir -p modules/monmodule
```

### 2. Créer la classe du module

Créez le fichier `modules/monmodule/MonmoduleModule.php` :

```php
<?php
namespace Modules\Monmodule;

use Core\Module;

class MonmoduleModule extends Module {
    protected $type = 'monmodule';
    protected $name = 'Mon Module';

    public function render() {
        // Code de rendu du module
        return '<div class="module module-monmodule">...</div>';
    }

    public function renderEditForm() {
        // Formulaire d'édition
        return '<div class="form-group">...</div>';
    }

    public function validate($data) {
        // Validation des données
        return true;
    }
}
```

### 3. Enregistrer le module

Ajoutez votre module dans `config/modules.php` :

```php
'monmodule' => [
    'name' => 'Mon Module',
    'description' => 'Description de mon module',
    'icon' => 'fas fa-star',
    'class' => 'Modules\Monmodule\MonmoduleModule',
    'enabled' => true,
],
```

## Créer un nouveau thème

### 1. Créer la structure

```bash
mkdir -p themes/mon-theme/templates
mkdir -p themes/mon-theme/assets/{css,js}
```

### 2. Créer le template principal

Créez `themes/mon-theme/templates/page.php` :

```php
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page['title']; ?></title>
    <link rel="stylesheet" href="/themes/mon-theme/assets/css/style.css">
</head>
<body>
    <!-- Votre structure HTML -->
    <?php foreach ($modules as $moduleData): ?>
        <!-- Rendu des modules -->
    <?php endforeach; ?>
</body>
</html>
```

### 3. Activer le thème

Modifiez `config/app.php` :

```php
'theme' => 'mon-theme',
```

## API REST

NovoSito expose une API REST pour gérer les modules et les médias.

### Endpoints disponibles

#### Modules

- `POST /api/modules` - Créer un module
- `PUT /api/modules/{id}` - Mettre à jour un module
- `DELETE /api/modules/{id}` - Supprimer un module
- `POST /api/modules/reorder` - Réorganiser les modules

#### Médias

- `GET /api/media` - Liste des médias
- `POST /api/media/upload` - Upload un média
- `DELETE /api/media/{id}` - Supprimer un média

### Exemple d'utilisation

```javascript
// Créer un module
fetch('/api/modules', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        type: 'text',
        page_id: 1,
        content: { text: 'Mon contenu' },
        settings: { alignment: 'center' }
    })
})
.then(response => response.json())
.then(data => console.log(data));
```

## Configuration

### Configuration générale (`config/app.php`)

```php
return [
    'name' => 'Nom de votre site',
    'url' => 'http://votre-domaine.com',
    'theme' => 'default',
    'locale' => 'fr',
    'timezone' => 'Europe/Paris',
    'debug' => false, // Activé uniquement en développement
];
```

### Uploads (`config/app.php`)

```php
'uploads' => [
    'max_size' => 5242880, // 5 MB
    'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
],
```

## Sécurité

### Bonnes pratiques

1. **Changez les identifiants par défaut** immédiatement
2. **Désactivez le mode debug** en production
3. **Configurez HTTPS** pour sécuriser les connexions
4. **Sauvegardez régulièrement** votre base de données
5. **Mettez à jour** PHP et MySQL régulièrement
6. **Limitez les permissions** des fichiers et dossiers

### Protection contre les injections SQL

NovoSito utilise PDO avec des requêtes préparées pour éviter les injections SQL.

### Protection XSS

Toutes les données sont échappées avant l'affichage avec `htmlspecialchars()`.

## Dépannage

### Erreur 404 sur toutes les pages

- Vérifiez que `mod_rewrite` est activé
- Vérifiez que le fichier `.htaccess` est présent
- Vérifiez la configuration d'Apache (`AllowOverride All`)

### Erreur de connexion à la base de données

- Vérifiez les paramètres dans `config/database.php`
- Vérifiez que MySQL est en cours d'exécution
- Vérifiez les permissions de l'utilisateur MySQL

### Les uploads ne fonctionnent pas

- Vérifiez les permissions du dossier `uploads/`
- Vérifiez la taille maximale d'upload dans `php.ini` :
  ```ini
  upload_max_filesize = 10M
  post_max_size = 10M
  ```

## Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :

1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Committer vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## Roadmap

- [ ] Système d'authentification complet
- [ ] Gestion multi-sites
- [ ] Système de cache
- [ ] Éditeur de page en front-end
- [ ] Marketplace de modules et thèmes
- [ ] Support multilingue
- [ ] API GraphQL
- [ ] Builder de formulaires avancé
- [ ] Système de newsletters
- [ ] Analytics intégré

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## Support

Pour toute question ou problème :

- Ouvrez une issue sur GitHub
- Consultez la documentation
- Contactez l'équipe de support

## Auteur

Développé avec ❤️ pour simplifier la création de sites web.

---

**NovoSito CMS** - Créez votre site vitrine en quelques minutes !
