<?php
/**
 * Script de vérification de l'installation de NovoSito CMS
 * Exécutez ce fichier depuis votre navigateur : http://localhost/novosito/check.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$checks = [];

// En-tête
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de l'installation - NovoSito CMS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f5f7fa;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #7f8c8d;
            margin-bottom: 30px;
        }
        .check-item {
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 6px;
            border-left: 4px solid #ddd;
        }
        .check-item.success {
            background: #d4edda;
            border-color: #28a745;
        }
        .check-item.error {
            background: #f8d7da;
            border-color: #dc3545;
        }
        .check-item.warning {
            background: #fff3cd;
            border-color: #ffc107;
        }
        .check-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .check-message {
            font-size: 14px;
            color: #555;
        }
        .icon {
            margin-right: 8px;
        }
        .summary {
            margin-top: 30px;
            padding: 20px;
            background: #e3f2fd;
            border-radius: 6px;
        }
        .summary.success { background: #d4edda; }
        .summary.error { background: #f8d7da; }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        .section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Vérification de l'installation</h1>
        <p class="subtitle">NovoSito CMS - Diagnostic système</p>

        <div class="section">
            <div class="section-title">Configuration PHP</div>
<?php

// Vérification de la version PHP
$phpVersion = phpversion();
if (version_compare($phpVersion, '7.4.0', '>=')) {
    echo checkItem('success', 'Version PHP', "PHP $phpVersion ✓");
    $checks['php_version'] = true;
} else {
    echo checkItem('error', 'Version PHP', "PHP $phpVersion - Version 7.4+ requise");
    $checks['php_version'] = false;
}

// Extensions PHP requises
$requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'json'];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo checkItem('success', "Extension $ext", "Installée ✓");
        $checks["ext_$ext"] = true;
    } else {
        echo checkItem('error', "Extension $ext", "Non installée - Requis");
        $checks["ext_$ext"] = false;
    }
}

// mod_rewrite (difficile à détecter en PHP)
if (function_exists('apache_get_modules')) {
    if (in_array('mod_rewrite', apache_get_modules())) {
        echo checkItem('success', 'mod_rewrite', 'Activé ✓');
        $checks['mod_rewrite'] = true;
    } else {
        echo checkItem('error', 'mod_rewrite', 'Non activé - Requis pour les URLs');
        $checks['mod_rewrite'] = false;
    }
} else {
    echo checkItem('warning', 'mod_rewrite', 'Impossible à vérifier (non Apache?)');
    $checks['mod_rewrite'] = null;
}

?>
        </div>

        <div class="section">
            <div class="section-title">Fichiers et dossiers</div>
<?php

// Vérification des fichiers essentiels
$requiredFiles = [
    'index.php' => 'Point d\'entrée',
    '.htaccess' => 'Configuration Apache',
    'config/app.php' => 'Configuration application',
    'config/database.php' => 'Configuration base de données',
    'core/Database.php' => 'Classe Database',
    'core/Router.php' => 'Classe Router',
    'database/schema.sql' => 'Schéma de base de données',
];

foreach ($requiredFiles as $file => $description) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo checkItem('success', $description, "<code>$file</code> présent ✓");
        $checks["file_$file"] = true;
    } else {
        echo checkItem('error', $description, "<code>$file</code> manquant");
        $checks["file_$file"] = false;
    }
}

// Vérification des permissions
$writableDirs = ['uploads/', 'uploads/images/', 'uploads/documents/'];
foreach ($writableDirs as $dir) {
    $dirPath = __DIR__ . '/' . $dir;
    if (is_dir($dirPath) && is_writable($dirPath)) {
        echo checkItem('success', "Dossier $dir", "Accessible en écriture ✓");
        $checks["dir_$dir"] = true;
    } elseif (is_dir($dirPath)) {
        echo checkItem('warning', "Dossier $dir", "Non accessible en écriture");
        $checks["dir_$dir"] = false;
    } else {
        echo checkItem('error', "Dossier $dir", "N'existe pas");
        $checks["dir_$dir"] = false;
    }
}

?>
        </div>

        <div class="section">
            <div class="section-title">Configuration de la base de données</div>
<?php

// Vérification de la configuration
if (file_exists(__DIR__ . '/config/database.php')) {
    $dbConfig = require __DIR__ . '/config/database.php';

    echo checkItem('success', 'Fichier de configuration', 'Présent ✓');

    // Test de connexion
    try {
        $dsn = "mysql:host={$dbConfig['host']};charset={$dbConfig['charset']}";
        $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        echo checkItem('success', 'Connexion au serveur MySQL', 'Réussie ✓');
        $checks['mysql_connection'] = true;

        // Vérification de l'existence de la base de données
        $stmt = $pdo->query("SHOW DATABASES LIKE '{$dbConfig['database']}'");
        if ($stmt->rowCount() > 0) {
            echo checkItem('success', 'Base de données', "<code>{$dbConfig['database']}</code> existe ✓");
            $checks['database_exists'] = true;

            // Sélection de la base et vérification des tables
            $pdo->exec("USE {$dbConfig['database']}");
            $requiredTables = ['pages', 'modules', 'users', 'media', 'sites'];
            $stmt = $pdo->query("SHOW TABLES");
            $existingTables = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($requiredTables as $table) {
                if (in_array($table, $existingTables)) {
                    echo checkItem('success', "Table $table", "Existe ✓");
                    $checks["table_$table"] = true;
                } else {
                    echo checkItem('error', "Table $table", "N'existe pas - Exécutez database/install.php");
                    $checks["table_$table"] = false;
                }
            }

        } else {
            echo checkItem('error', 'Base de données', "<code>{$dbConfig['database']}</code> n'existe pas - Exécutez database/install.php");
            $checks['database_exists'] = false;
        }

    } catch (PDOException $e) {
        echo checkItem('error', 'Connexion MySQL', 'Échec : ' . $e->getMessage());
        $checks['mysql_connection'] = false;
    }
} else {
    echo checkItem('error', 'Configuration BDD', 'Fichier config/database.php manquant');
    $checks['db_config'] = false;
}

?>
        </div>

        <div class="section">
            <div class="section-title">Modules</div>
<?php

$modules = ['text', 'image', 'gallery', 'contact', 'map'];
foreach ($modules as $module) {
    $moduleClass = ucfirst($module) . 'Module';
    $modulePath = __DIR__ . "/modules/$module/{$moduleClass}.php";

    if (file_exists($modulePath)) {
        echo checkItem('success', "Module $module", "Présent ✓");
        $checks["module_$module"] = true;
    } else {
        echo checkItem('warning', "Module $module", "Manquant (optionnel)");
        $checks["module_$module"] = false;
    }
}

?>
        </div>

        <div class="section">
            <div class="section-title">Résumé</div>
<?php

$totalChecks = count($checks);
$successChecks = count(array_filter($checks, function($v) { return $v === true; }));
$errorChecks = count(array_filter($checks, function($v) { return $v === false; }));

$percentage = round(($successChecks / $totalChecks) * 100);

if ($percentage == 100) {
    $summaryClass = 'success';
    $message = "✅ Parfait ! Votre installation est complète et fonctionnelle.";
    $nextSteps = "<strong>Prochaines étapes :</strong><br>
        1. Supprimez ce fichier check.php pour des raisons de sécurité<br>
        2. Accédez au back-office : <a href='/novosito/admin'>/admin</a><br>
        3. Connectez-vous avec : admin@example.com / admin123<br>
        4. Changez immédiatement le mot de passe !";
} elseif ($percentage >= 80) {
    $summaryClass = 'warning';
    $message = "⚠️ Installation presque complète. Quelques avertissements mineurs.";
    $nextSteps = "<strong>Actions recommandées :</strong><br>
        1. Corrigez les avertissements ci-dessus si possible<br>
        2. Testez l'accès au site et à l'admin<br>
        3. Vérifiez les logs d'erreur si des problèmes surviennent";
} else {
    $summaryClass = 'error';
    $message = "❌ Installation incomplète. Des erreurs critiques ont été détectées.";
    $nextSteps = "<strong>Actions requises :</strong><br>
        1. Corrigez toutes les erreurs signalées en rouge<br>
        2. Consultez le fichier INSTALL.md pour plus de détails<br>
        3. Relancez cette vérification après les corrections";
}

?>
            <div class="summary <?php echo $summaryClass; ?>">
                <div style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">
                    <?php echo $message; ?>
                </div>
                <div style="margin-bottom: 15px;">
                    <?php echo $successChecks; ?> / <?php echo $totalChecks; ?> vérifications réussies (<?php echo $percentage; ?>%)
                </div>
                <div style="font-size: 14px;">
                    <?php echo $nextSteps; ?>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Informations système</div>
            <table style="width: 100%; font-size: 14px;">
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Version PHP :</td>
                    <td style="padding: 8px;"><?php echo PHP_VERSION; ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Serveur Web :</td>
                    <td style="padding: 8px;"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Inconnu'; ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">OS Serveur :</td>
                    <td style="padding: 8px;"><?php echo PHP_OS; ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Upload max :</td>
                    <td style="padding: 8px;"><?php echo ini_get('upload_max_filesize'); ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Post max :</td>
                    <td style="padding: 8px;"><?php echo ini_get('post_max_size'); ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Memory limit :</td>
                    <td style="padding: 8px;"><?php echo ini_get('memory_limit'); ?></td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #eee; text-align: center; color: #7f8c8d; font-size: 14px;">
            NovoSito CMS v1.0.0 - Script de vérification de l'installation
        </div>
    </div>
</body>
</html>

<?php

function checkItem($type, $title, $message) {
    $icons = [
        'success' => '✓',
        'error' => '✗',
        'warning' => '⚠'
    ];

    $icon = $icons[$type] ?? '•';

    return "
    <div class='check-item $type'>
        <div class='check-title'><span class='icon'>$icon</span>$title</div>
        <div class='check-message'>$message</div>
    </div>
    ";
}
