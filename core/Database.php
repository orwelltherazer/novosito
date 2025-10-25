<?php
/**
 * Database Class
 * Gestion de la connexion et des requêtes à la base de données
 */

namespace Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $config = require __DIR__ . '/../config/database.php';

        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
            $this->connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            throw new \Exception("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Récupère l'instance singleton de la base de données
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retourne la connexion PDO
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Exécute une requête SELECT
     */
    public function select($query, $params = []) {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Exécute une requête SELECT et retourne une seule ligne
     */
    public function selectOne($query, $params = []) {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Exécute une requête INSERT, UPDATE ou DELETE
     */
    public function execute($query, $params = []) {
        $stmt = $this->connection->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Retourne le dernier ID inséré
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }

    /**
     * Démarre une transaction
     */
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }

    /**
     * Valide une transaction
     */
    public function commit() {
        return $this->connection->commit();
    }

    /**
     * Annule une transaction
     */
    public function rollback() {
        return $this->connection->rollBack();
    }
}
