# Changelog

Tous les changements notables de ce projet seront documentés dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/),
et ce projet adhère au [Semantic Versioning](https://semver.org/lang/fr/).

## [1.0.0] - 2025-01-25

### Ajouté

#### Core
- Architecture MVC complète
- Système de routing personnalisé
- Gestion de base de données avec PDO
- Autoloader pour le chargement des classes
- Système de vues avec templates

#### Modules
- Module Texte avec éditeur WYSIWYG
- Module Image avec gestion des légendes
- Module Galerie d'images
- Module Formulaire de contact
- Module Carte géographique (OpenStreetMap)
- Module Vidéo (YouTube/Vimeo)
- Module HTML personnalisé

#### Administration
- Tableau de bord avec statistiques
- Gestion des pages (CRUD complet)
- Éditeur de modules par drag & drop
- Gestion des médias
- Interface utilisateur moderne et responsive

#### Front-end
- Thème par défaut responsive
- Intégration Bootstrap 5
- Lightbox pour les galeries
- Optimisation SEO
- Structure sémantique HTML5

#### Sécurité
- Protection contre les injections SQL (requêtes préparées)
- Protection XSS (échappement des données)
- Validation des uploads
- Gestion des sessions sécurisée

#### Documentation
- README complet avec guide d'utilisation
- Guide d'installation détaillé (INSTALL.md)
- Documentation de l'API
- Exemples de création de modules et thèmes
- Fichier CHANGELOG

### Structure de la base de données
- Table `users` : Gestion des utilisateurs
- Table `sites` : Support multi-sites
- Table `pages` : Gestion des pages
- Table `modules` : Système modulaire
- Table `media` : Bibliothèque de médias
- Table `menus` : Gestion des menus
- Table `menu_items` : Éléments de menu
- Table `settings` : Paramètres du site
- Table `contact_submissions` : Formulaires de contact
- Table `sessions` : Gestion des sessions

### Configuration
- Configuration de la base de données
- Configuration générale de l'application
- Configuration des modules
- Paramètres d'upload
- Paramètres SEO

## [À venir]

### Version 1.1.0
- [ ] Système d'authentification complet avec gestion des rôles
- [ ] Éditeur de page en front-end (inline editing)
- [ ] Gestionnaire de menus dans l'admin
- [ ] Système de cache pour les performances
- [ ] Builder de formulaires avancé

### Version 1.2.0
- [ ] Support multilingue
- [ ] Gestion multi-sites complète
- [ ] Marketplace de modules
- [ ] Système de révisions de pages
- [ ] Planification de publication

### Version 2.0.0
- [ ] Refonte complète de l'interface admin
- [ ] API GraphQL
- [ ] Page builder visuel (type Elementor)
- [ ] Système de thèmes dynamiques
- [ ] Analytics intégré

---

[1.0.0]: https://github.com/votre-username/novosito/releases/tag/v1.0.0
