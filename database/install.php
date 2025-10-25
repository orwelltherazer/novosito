<?php
/**
 * Script d'installation de la base de données
 * À exécuter une seule fois pour créer les tables
 */

require_once __DIR__ . '/../core/Autoloader.php';
Core\Autoloader::register();

$config = require __DIR__ . '/../config/database.php';

try {
    // Connexion au serveur MySQL sans sélectionner de base de données
    $pdo = new PDO(
        "mysql:host={$config['host']};charset={$config['charset']}",
        $config['username'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "Connexion au serveur MySQL réussie.\n\n";

    // Lecture du fichier SQL
    $sql = file_get_contents(__DIR__ . '/schema.sql');

    // Exécution des requêtes
    $pdo->exec($sql);

    echo "Base de données créée avec succès!\n";
    echo "Utilisateur par défaut:\n";
    echo "  - Username: admin\n";
    echo "  - Email: admin@example.com\n";
    echo "  - Password: admin123\n\n";
    echo "IMPORTANT: Changez ce mot de passe après votre première connexion!\n";

} catch (PDOException $e) {
    echo "Erreur lors de l'installation: " . $e->getMessage() . "\n";
    exit(1);
}
