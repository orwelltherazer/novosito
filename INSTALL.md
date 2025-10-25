# Guide d'installation NovoSito CMS

## Installation rapide

### Prérequis

Avant de commencer, assurez-vous d'avoir :

- **XAMPP** (ou WAMP, LAMP, MAMP) installé
- **PHP 7.4+** activé
- **MySQL/MariaDB** en cours d'exécution
- **Apache** avec mod_rewrite activé

### Étape 1 : Téléchargement

Le projet est déjà dans votre dossier `C:\xampp2\htdocs\novosito`.

### Étape 2 : Configuration de la base de données

1. **Ouvrez phpMyAdmin** : `http://localhost/phpmyadmin`

2. **Créez une nouvelle base de données** :
   - Nom : `novosito_cms`
   - Collation : `utf8mb4_unicode_ci`

3. **Configurez les identifiants** dans `config/database.php` :

```php
return [
    'host' => 'localhost',
    'database' => 'novosito_cms',
    'username' => 'root',      // Votre utilisateur MySQL
    'password' => '',          // Votre mot de passe MySQL
    'charset' => 'utf8mb4',
];
```

### Étape 3 : Installation de la base de données

**Option 1 : Via phpMyAdmin**

1. Ouvrez phpMyAdmin
2. Sélectionnez la base `novosito_cms`
3. Cliquez sur "Importer"
4. Sélectionnez le fichier `database/schema.sql`
5. Cliquez sur "Exécuter"

**Option 2 : Via la ligne de commande**

```bash
cd C:\xampp2\htdocs\novosito
php database/install.php
```

### Étape 4 : Configuration du serveur

#### Configuration Apache (XAMPP)

1. Vérifiez que `mod_rewrite` est activé dans `httpd.conf` :

```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

2. Assurez-vous que `AllowOverride` est configuré :

```apache
<Directory "C:/xampp2/htdocs">
    AllowOverride All
    Require all granted
</Directory>
```

3. Redémarrez Apache depuis le panneau de contrôle XAMPP

#### Permissions des dossiers

Sur Windows, aucune action nécessaire. Sur Linux/Mac :

```bash
chmod -R 755 uploads/
chmod -R 755 themes/
```

### Étape 5 : Configuration de l'application

Éditez `config/app.php` :

```php
return [
    'name' => 'Mon Site',
    'url' => 'http://localhost/novosito',  // Adaptez selon votre configuration
    'theme' => 'default',
    'locale' => 'fr',
    'timezone' => 'Europe/Paris',
    'debug' => true,  // Mettez à false en production
];
```

### Étape 6 : Accès au site

**Site public** : `http://localhost/novosito`

**Back-office** : `http://localhost/novosito/admin`

**Identifiants par défaut** :
- Email : `admin@example.com`
- Password : `admin123`

⚠️ **Changez immédiatement ces identifiants !**

## Vérification de l'installation

### 1. Page d'accueil

Accédez à `http://localhost/novosito` - Vous devriez voir la page d'accueil par défaut.

### 2. Back-office

Accédez à `http://localhost/novosito/admin` - Vous devriez voir le tableau de bord.

### 3. Test de création de page

1. Connectez-vous au back-office
2. Allez dans "Pages"
3. Cliquez sur "Nouvelle page"
4. Créez une page de test
5. Ajoutez des modules
6. Publiez la page

## Dépannage

### Erreur "404 Not Found" sur toutes les pages

**Solution** : mod_rewrite n'est pas activé

1. Ouvrez `C:\xampp2\apache\conf\httpd.conf`
2. Recherchez `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Supprimez le `#` au début
4. Redémarrez Apache

### Erreur de connexion à la base de données

**Solution** : Vérifiez vos identifiants

1. Ouvrez phpMyAdmin
2. Vérifiez que vous pouvez vous connecter avec les mêmes identifiants
3. Vérifiez que la base `novosito_cms` existe
4. Vérifiez `config/database.php`

### Les images ne s'uploadent pas

**Solutions possibles** :

1. **Vérifiez les permissions** (Linux/Mac uniquement) :
```bash
chmod -R 755 uploads/
```

2. **Vérifiez la taille maximale d'upload** :
   - Éditez `C:\xampp2\php\php.ini`
   - Modifiez ces lignes :
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```
   - Redémarrez Apache

### Erreur "Class not found"

**Solution** : Problème d'autoloader

1. Vérifiez que tous les fichiers sont présents
2. Vérifiez les majuscules/minuscules dans les noms de fichiers
3. Redémarrez Apache

### Page blanche sans erreur

**Solution** : Activez l'affichage des erreurs

1. Éditez `config/app.php` :
```php
'debug' => true,
```

2. Ou modifiez temporairement `index.php` :
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Configuration avancée

### Activer HTTPS (recommandé pour la production)

1. Générez un certificat SSL
2. Configurez Apache pour HTTPS
3. Modifiez `config/app.php` :
```php
'url' => 'https://votre-domaine.com',
```

### Optimisations de performance

1. **Activez la compression GZIP** (déjà dans `.htaccess`)

2. **Configurez le cache du navigateur** (déjà dans `.htaccess`)

3. **Optimisez PHP** dans `php.ini` :
```ini
opcache.enable=1
opcache.memory_consumption=128
```

### Sauvegarde

#### Sauvegarde de la base de données

**Via phpMyAdmin** :
1. Sélectionnez `novosito_cms`
2. Cliquez sur "Exporter"
3. Choisissez "Rapide" et "SQL"
4. Cliquez sur "Exécuter"

**Via ligne de commande** :
```bash
mysqldump -u root -p novosito_cms > backup.sql
```

#### Sauvegarde des fichiers

Copiez simplement le dossier complet `novosito/` et en particulier le dossier `uploads/`.

## Migration vers un serveur de production

### 1. Exportez la base de données

```bash
mysqldump -u root -p novosito_cms > novosito_export.sql
```

### 2. Transférez les fichiers

Uploadez tous les fichiers sur votre serveur via FTP/SFTP.

### 3. Importez la base de données sur le serveur

```bash
mysql -u utilisateur -p nouvelle_base < novosito_export.sql
```

### 4. Mettez à jour la configuration

Éditez `config/database.php` et `config/app.php` avec les nouveaux paramètres.

### 5. Configurez le serveur

- Vérifiez que mod_rewrite est activé
- Configurez HTTPS
- Sécurisez les permissions

### 6. Désactivez le mode debug

Dans `config/app.php` :
```php
'debug' => false,
```

## Support

Si vous rencontrez des problèmes :

1. Consultez ce guide de dépannage
2. Vérifiez les logs d'erreur Apache : `C:\xampp2\apache\logs\error.log`
3. Vérifiez les logs PHP
4. Contactez le support

## Prochaines étapes

Une fois l'installation terminée :

1. ✅ Changez le mot de passe admin
2. ✅ Créez votre première page
3. ✅ Ajoutez des modules
4. ✅ Personnalisez le thème
5. ✅ Configurez le formulaire de contact
6. ✅ Optimisez le SEO

Bon développement avec NovoSito CMS ! 🚀
