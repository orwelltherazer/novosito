# Guide de démarrage rapide - NovoSito CMS

## Installation en 5 minutes

### Étape 1 : Vérification

Ouvrez votre navigateur et accédez à :

```
http://localhost/novosito/check.php
```

Ce script vérifiera automatiquement que tout est en place. Suivez les instructions affichées.

### Étape 2 : Installation de la base de données

**Option A : Via le script PHP**

```bash
cd C:\xampp2\htdocs\novosito
php database/install.php
```

**Option B : Via phpMyAdmin**

1. Ouvrez phpMyAdmin : `http://localhost/phpmyadmin`
2. Créez une base de données : `novosito_cms`
3. Importez le fichier : `database/schema.sql`

### Étape 3 : Configuration

Éditez `config/database.php` si vos identifiants MySQL sont différents :

```php
'username' => 'root',      // Votre utilisateur
'password' => '',          // Votre mot de passe
```

### Étape 4 : Première connexion

**Site public** : `http://localhost/novosito`

**Administration** : `http://localhost/novosito/admin`

Identifiants par défaut :
- Email : `admin@example.com`
- Mot de passe : `admin123`

⚠️ **Changez ces identifiants immédiatement !**

## Premiers pas

### 1. Créer votre première page

1. Connectez-vous à l'admin
2. Cliquez sur **"Pages"** → **"Nouvelle page"**
3. Remplissez :
   - Titre : `À propos`
   - Statut : `Publié`
4. Cliquez sur **"Créer la page"**

### 2. Ajouter du contenu

1. Cliquez sur **"Ajouter un module"**
2. Choisissez **"Texte"**
3. Rédigez votre contenu
4. Sauvegardez

### 3. Autres modules disponibles

#### Module Image
- Uploadez une image
- Ajoutez une légende
- Configurez l'alignement

#### Module Galerie
- Créez une galerie d'images
- Configurez le nombre de colonnes
- Ajustez l'espacement

#### Module Contact
- Formulaire de contact prêt à l'emploi
- Personnalisez les champs
- Recevez les messages par email

#### Module Carte
- Ajoutez une carte interactive
- Entrez une adresse ou des coordonnées
- Utilisez OpenStreetMap (gratuit)

### 4. Réorganiser les modules

Sur la page d'édition, glissez-déposez les modules pour changer leur ordre.

### 5. Gérer les médias

1. Accédez à **"Médias"**
2. Uploadez vos fichiers
3. Utilisez-les dans vos modules

## Personnalisation

### Modifier le nom du site

Éditez `config/app.php` :

```php
'name' => 'Mon Super Site',
'url' => 'http://votre-domaine.com',
```

### Changer le thème

Le thème par défaut se trouve dans `themes/default/`.

Pour personnaliser :

1. **CSS** : Éditez `themes/default/assets/css/style.css`
2. **HTML** : Modifiez `themes/default/templates/page.php`
3. **JavaScript** : Personnalisez `themes/default/assets/js/script.js`

### Créer un nouveau module

Voir le fichier `ARCHITECTURE.md` pour un guide détaillé.

## Exemples de sites

### Site vitrine simple

1. **Accueil** : Module Texte + Module Image + Module Contact
2. **Services** : Module Texte avec liste à puces
3. **Galerie** : Module Galerie avec vos photos
4. **Contact** : Module Contact + Module Carte

### Site d'artisan

1. **Accueil** : Présentation avec images
2. **Réalisations** : Galerie de projets
3. **Tarifs** : Module Texte avec tableaux
4. **Localisation** : Carte avec adresse
5. **Contact** : Formulaire

### Site d'association

1. **Accueil** : Actualités
2. **Qui sommes-nous** : Histoire et équipe
3. **Événements** : Galerie photos
4. **Adhésion** : Formulaire de contact
5. **Localisation** : Carte du lieu

## Dépannage rapide

### Page blanche
- Activez le mode debug dans `config/app.php`
- Vérifiez les logs d'erreur

### 404 sur toutes les pages
- Vérifiez que mod_rewrite est activé
- Vérifiez le fichier `.htaccess`

### Les images ne s'uploadent pas
- Vérifiez les permissions du dossier `uploads/`
- Augmentez `upload_max_filesize` dans php.ini

### Erreur de connexion BDD
- Vérifiez `config/database.php`
- Vérifiez que MySQL est démarré

## Ressources utiles

### Documentation
- [README.md](README.md) : Documentation complète
- [INSTALL.md](INSTALL.md) : Guide d'installation détaillé
- [ARCHITECTURE.md](ARCHITECTURE.md) : Architecture technique
- [CONTRIBUTING.md](CONTRIBUTING.md) : Guide de contribution

### Fichiers importants
- `index.php` : Point d'entrée
- `config/app.php` : Configuration générale
- `config/database.php` : Configuration BDD
- `config/modules.php` : Modules disponibles

### Commandes utiles

Installer la BDD :
```bash
php database/install.php
```

Vérifier l'installation :
```
http://localhost/novosito/check.php
```

### Support

- Consultez la documentation
- Vérifiez les issues GitHub
- Ouvrez une nouvelle issue si besoin

## Checklist de mise en production

Avant de mettre votre site en ligne :

- [ ] Changez le mot de passe admin
- [ ] Désactivez le mode debug (`config/app.php`)
- [ ] Supprimez `check.php`
- [ ] Configurez HTTPS
- [ ] Sauvegardez la base de données
- [ ] Testez tous les formulaires
- [ ] Vérifiez le SEO (titres, descriptions)
- [ ] Testez sur mobile
- [ ] Configurez les emails du formulaire de contact
- [ ] Ajoutez un favicon
- [ ] Créez une page 404 personnalisée

## Prochaines étapes

Une fois votre site créé :

1. **Optimisez le SEO**
   - Remplissez les titres et descriptions
   - Ajoutez des balises alt aux images
   - Créez un sitemap

2. **Améliorez les performances**
   - Compressez les images
   - Activez le cache
   - Utilisez un CDN

3. **Sécurisez votre site**
   - Activez HTTPS
   - Mettez à jour régulièrement
   - Sauvegardez fréquemment

4. **Personnalisez**
   - Créez votre propre thème
   - Développez des modules personnalisés
   - Ajoutez des fonctionnalités

## Exemples de code

### Ajouter un lien dans le menu (à faire manuellement pour l'instant)

Éditez `themes/default/templates/page.php` :

```php
<ul class="nav">
    <li class="nav-item">
        <a href="/" class="nav-link">Accueil</a>
    </li>
    <li class="nav-item">
        <a href="/page/a-propos" class="nav-link">À propos</a>
    </li>
    <li class="nav-item">
        <a href="/page/contact" class="nav-link">Contact</a>
    </li>
</ul>
```

### Modifier les couleurs du thème

Éditez `themes/default/assets/css/style.css` :

```css
:root {
    --primary-color: #0d6efd;    /* Changez cette couleur */
    --secondary-color: #6c757d;
    --dark-color: #212529;
}
```

### Ajouter du JavaScript personnalisé

Ajoutez votre code dans `themes/default/assets/js/script.js` :

```javascript
document.addEventListener('DOMContentLoaded', function() {
    // Votre code ici
    console.log('Mon site est chargé !');
});
```

## Félicitations ! 🎉

Vous avez maintenant un CMS fonctionnel et prêt à l'emploi !

N'hésitez pas à :
- Explorer toutes les fonctionnalités
- Personnaliser votre site
- Contribuer au projet
- Partager vos créations

**Bon développement avec NovoSito CMS !**

---

*Pour toute question, consultez la [documentation complète](README.md) ou ouvrez une issue sur GitHub.*
