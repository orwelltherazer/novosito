# Architecture de NovoSito CMS

## Vue d'ensemble

NovoSito CMS est construit sur une architecture MVC (Model-View-Controller) moderne et modulaire, conçue pour être :
- **Légère** : Pas de dépendances externes lourdes
- **Performante** : Optimisée pour la rapidité
- **Extensible** : Système de modules et thèmes
- **Maintenable** : Code structuré et documenté

## Stack technique

### Backend
- **PHP 7.4+** : Langage serveur avec orienté objet
- **PDO** : Couche d'abstraction de base de données
- **MySQL/MariaDB** : Base de données relationnelle

### Frontend
- **HTML5** : Structure sémantique
- **CSS3** : Styles modernes
- **JavaScript (ES6+)** : Interactivité
- **Bootstrap 5** : Framework CSS responsive

### Bibliothèques tierces
- **TinyMCE** : Éditeur WYSIWYG
- **Font Awesome** : Icônes
- **Leaflet** : Cartes géographiques (OpenStreetMap)

## Architecture des dossiers

```
novosito/
│
├── admin/                      # Back-office d'administration
│   ├── assets/                 # Ressources de l'admin
│   │   ├── css/
│   │   │   └── admin.css       # Styles de l'admin
│   │   └── js/
│   │       └── admin.js        # Scripts de l'admin
│   ├── controllers/            # Contrôleurs admin (non utilisé actuellement)
│   └── views/                  # Vues de l'admin
│       ├── pages/              # Vues de gestion des pages
│       ├── partials/           # Composants réutilisables
│       └── dashboard.php       # Tableau de bord
│
├── api/                        # Points d'accès API (structure vide)
│
├── app/                        # Application principale
│   ├── Controllers/            # Contrôleurs
│   │   ├── Admin/              # Contrôleurs de l'admin
│   │   │   ├── DashboardController.php
│   │   │   └── PageController.php
│   │   ├── Api/                # Contrôleurs API
│   │   │   ├── MediaController.php
│   │   │   └── ModuleController.php
│   │   ├── HomeController.php
│   │   └── PageController.php
│   │
│   └── Models/                 # Modèles
│       ├── Media.php
│       ├── Page.php
│       └── User.php
│
├── assets/                     # Ressources publiques
│   ├── css/
│   ├── img/
│   └── js/
│
├── config/                     # Configuration
│   ├── app.php                 # Configuration générale
│   ├── database.php            # Configuration BDD
│   └── modules.php             # Configuration des modules
│
├── core/                       # Cœur du CMS
│   ├── Autoloader.php          # Chargement automatique des classes
│   ├── Controller.php          # Contrôleur de base
│   ├── Database.php            # Gestion de la BDD
│   ├── Model.php               # Modèle de base
│   ├── Module.php              # Système de modules
│   ├── Router.php              # Routeur
│   └── View.php                # Gestion des vues
│
├── database/                   # Base de données
│   ├── install.php             # Script d'installation
│   └── schema.sql              # Structure de la BDD
│
├── modules/                    # Modules disponibles
│   ├── contact/
│   │   └── ContactModule.php
│   ├── gallery/
│   │   └── GalleryModule.php
│   ├── html/
│   ├── image/
│   │   └── ImageModule.php
│   ├── map/
│   │   └── MapModule.php
│   ├── text/
│   │   └── TextModule.php
│   └── video/
│
├── themes/                     # Thèmes
│   └── default/                # Thème par défaut
│       ├── assets/
│       │   ├── css/
│       │   │   └── style.css
│       │   └── js/
│       │       └── script.js
│       └── templates/
│           └── page.php
│
├── uploads/                    # Fichiers uploadés
│   ├── documents/
│   ├── images/
│   └── temp/
│
├── .htaccess                   # Configuration Apache
├── .gitignore                  # Fichiers ignorés par Git
├── ARCHITECTURE.md             # Ce fichier
├── CHANGELOG.md                # Historique des changements
├── CONTRIBUTING.md             # Guide de contribution
├── index.php                   # Point d'entrée
├── INSTALL.md                  # Guide d'installation
├── LICENSE                     # Licence MIT
└── README.md                   # Documentation principale
```

## Flux de données

### Requête HTTP → Réponse

```
1. Requête HTTP
   ↓
2. index.php (Point d'entrée)
   ↓
3. Autoloader (Chargement des classes)
   ↓
4. Router (Analyse de l'URL)
   ↓
5. Controller (Logique métier)
   ↓
6. Model (Interaction avec la BDD)
   ↓
7. View (Rendu HTML)
   ↓
8. Réponse HTTP
```

### Exemple concret : Affichage d'une page

```
GET /page/a-propos
   ↓
Router détecte la route /page/{slug}
   ↓
PageController::show($params)
   ↓
Page Model → SELECT * FROM pages WHERE slug = 'a-propos'
   ↓
Récupération des modules de la page
   ↓
Chargement du template (themes/default/templates/page.php)
   ↓
Rendu de chaque module via Module::render()
   ↓
HTML final envoyé au navigateur
```

## Composants du Core

### 1. Autoloader

**Fichier** : `core/Autoloader.php`

**Rôle** : Charge automatiquement les classes PHP sans nécessiter de `require` manuel.

**Fonctionnement** :
- Convertit les namespaces en chemins de fichiers
- Recherche dans les dossiers configurés
- Charge la classe si trouvée

### 2. Router

**Fichier** : `core/Router.php`

**Rôle** : Gère le routage des URLs vers les contrôleurs.

**Fonctionnalités** :
- Routes GET, POST, PUT, DELETE
- Paramètres dynamiques dans les URLs (`{id}`, `{slug}`)
- Callbacks ou contrôleurs

**Exemple** :
```php
$router->get('/page/{slug}', 'PageController@show');
```

### 3. Database

**Fichier** : `core/Database.php`

**Rôle** : Couche d'abstraction pour la base de données.

**Pattern** : Singleton (une seule instance)

**Fonctionnalités** :
- Connexion PDO
- Requêtes préparées (sécurité)
- Transactions
- Méthodes helper (`select`, `selectOne`, `execute`)

### 4. Controller

**Fichier** : `core/Controller.php`

**Rôle** : Contrôleur de base dont héritent tous les contrôleurs.

**Fonctionnalités** :
- Accès à la base de données
- Rendu de vues
- Réponses JSON
- Redirections
- Gestion des données POST/GET

### 5. Model

**Fichier** : `core/Model.php`

**Rôle** : Modèle de base pour l'interaction avec la BDD.

**Pattern** : Active Record

**Fonctionnalités** :
- CRUD de base (`all`, `find`, `create`, `update`, `delete`)
- Requêtes avec conditions (`where`)
- Comptage (`count`)

### 6. View

**Fichier** : `core/View.php`

**Rôle** : Gère le rendu des vues.

**Fonctionnalités** :
- Rendu de templates PHP
- Extraction de variables
- Support multi-thèmes
- Helpers (assets, échappement XSS)

### 7. Module

**Fichier** : `core/Module.php`

**Rôle** : Classe abstraite pour tous les modules.

**Méthodes obligatoires** :
- `render()` : Affichage du module
- `renderEditForm()` : Formulaire d'édition
- `validate()` : Validation des données

## Système de modules

### Cycle de vie d'un module

```
1. Création
   - Utilisateur clique "Ajouter un module"
   - Type de module sélectionné
   - Enregistrement en BDD

2. Configuration
   - Utilisateur édite le module
   - Formulaire généré via renderEditForm()
   - Données validées via validate()
   - Sauvegarde en BDD

3. Affichage
   - Module chargé depuis la BDD
   - Instanciation de la classe du module
   - Appel de render()
   - HTML injecté dans la page

4. Modification
   - Réorganisation par drag & drop
   - Mise à jour de l'ordre
   - Sauvegarde en BDD

5. Suppression
   - Utilisateur supprime le module
   - Suppression de la BDD
```

### Création d'un module personnalisé

**Étape 1** : Créer la classe

```php
<?php
namespace Modules\Exemple;

use Core\Module;

class ExempleModule extends Module {
    protected $type = 'exemple';
    protected $name = 'Exemple';

    public function render() {
        $text = $this->content['text'] ?? '';
        return "<div class='exemple'>{$text}</div>";
    }

    public function renderEditForm() {
        $text = $this->content['text'] ?? '';
        return '<input type="text" name="content[text]" value="' . htmlspecialchars($text) . '">';
    }

    public function validate($data) {
        return !empty($data['content']['text']);
    }
}
```

**Étape 2** : Enregistrer dans `config/modules.php`

```php
'exemple' => [
    'name' => 'Exemple',
    'description' => 'Module d\'exemple',
    'icon' => 'fas fa-star',
    'class' => 'Modules\Exemple\ExempleModule',
    'enabled' => true,
],
```

## Base de données

### Schéma relationnel

```
┌─────────┐       ┌─────────┐       ┌─────────┐
│  sites  │───┬───│  pages  │───┬───│ modules │
└─────────┘   │   └─────────┘   │   └─────────┘
              │                 │
              │   ┌─────────┐   │
              └───│  menus  │   │
                  └─────────┘   │
                       │        │
                  ┌────────────┐│
                  │ menu_items ││
                  └────────────┘│
                                │
              ┌──────────────────┘
              │
         ┌────────┐
         │ media  │
         └────────┘
```

### Tables principales

#### `pages`
- **id** : Identifiant unique
- **site_id** : Référence au site
- **title** : Titre de la page
- **slug** : URL de la page
- **content** : Contenu (non utilisé directement)
- **status** : Statut (draft, published)
- **is_homepage** : Page d'accueil ?

#### `modules`
- **id** : Identifiant unique
- **page_id** : Référence à la page
- **type** : Type de module
- **content** : Contenu JSON
- **settings** : Paramètres JSON
- **order_position** : Ordre d'affichage

#### `media`
- **id** : Identifiant unique
- **filename** : Nom du fichier
- **file_path** : Chemin du fichier
- **type** : Type (image, document)
- **width, height** : Dimensions (pour images)

## Sécurité

### Protection SQL Injection

Utilisation de requêtes préparées :

```php
// ✅ SÉCURISÉ
$db->selectOne("SELECT * FROM pages WHERE id = :id", ['id' => $id]);

// ❌ DANGEREUX
$db->selectOne("SELECT * FROM pages WHERE id = $id");
```

### Protection XSS

Échappement systématique :

```php
// ✅ SÉCURISÉ
echo htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

// ❌ DANGEREUX
echo $data;
```

### Upload de fichiers

- Validation du type MIME
- Limitation de taille
- Nom de fichier sécurisé (uniqid)
- Stockage hors racine web si possible

## Performance

### Optimisations actuelles

1. **PDO en mode préparé** : Moins de requêtes
2. **Pas de framework lourd** : Chargement rapide
3. **Cache navigateur** : Headers via .htaccess
4. **Compression GZIP** : Réduction de la bande passante
5. **CSS/JS externes** : Mise en cache

### Optimisations futures

1. **Cache applicatif** : Memcached ou Redis
2. **Lazy loading** : Images chargées à la demande
3. **Minification** : CSS/JS compressés
4. **CDN** : Distribution de contenu
5. **Database indexing** : Optimisation des requêtes

## Extensibilité

### Points d'extension

1. **Modules** : Nouveau type de contenu
2. **Thèmes** : Nouvelle apparence
3. **Contrôleurs** : Nouvelles routes
4. **Modèles** : Nouvelles tables
5. **Middlewares** : (À implémenter) Logique entre requête/réponse

### Hooks (futur)

```php
// Exemple de système de hooks
add_hook('before_page_render', function($page) {
    // Logique personnalisée
});
```

## Roadmap technique

### Court terme (v1.1)
- [ ] Système d'authentification complet
- [ ] Middleware pour les permissions
- [ ] Cache applicatif
- [ ] API REST complète

### Moyen terme (v1.2)
- [ ] Système de plugins
- [ ] Events & Listeners
- [ ] Queue system
- [ ] Migration system

### Long terme (v2.0)
- [ ] Headless CMS option
- [ ] GraphQL API
- [ ] Multi-database support
- [ ] Microservices architecture

---

Cette architecture est conçue pour évoluer tout en restant simple et maintenable.
