<?php
/**
 * Model Class
 * Modèle de base dont héritent tous les modèles
 */

namespace Core;

class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Récupère tous les enregistrements
     */
    public function all($orderBy = null) {
        $query = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $query .= " ORDER BY {$orderBy}";
        }
        return $this->db->select($query);
    }

    /**
     * Récupère un enregistrement par ID
     */
    public function find($id) {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->selectOne($query, ['id' => $id]);
    }

    /**
     * Récupère les enregistrements selon des critères
     */
    public function where($conditions, $orderBy = null) {
        $query = "SELECT * FROM {$this->table} WHERE ";
        $params = [];
        $whereClauses = [];

        foreach ($conditions as $key => $value) {
            $whereClauses[] = "{$key} = :{$key}";
            $params[$key] = $value;
        }

        $query .= implode(' AND ', $whereClauses);

        if ($orderBy) {
            $query .= " ORDER BY {$orderBy}";
        }

        return $this->db->select($query, $params);
    }

    /**
     * Crée un nouvel enregistrement
     */
    public function create($data) {
        $columns = array_keys($data);
        $values = array_values($data);

        $columnList = implode(', ', $columns);
        $placeholders = ':' . implode(', :', $columns);

        $query = "INSERT INTO {$this->table} ({$columnList}) VALUES ({$placeholders})";

        $params = array_combine(
            array_map(function($col) { return ":{$col}"; }, $columns),
            $values
        );

        if ($this->db->execute($query, $params)) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Met à jour un enregistrement
     */
    public function update($id, $data) {
        $setClauses = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            $setClauses[] = "{$key} = :{$key}";
            $params[$key] = $value;
        }

        $setString = implode(', ', $setClauses);
        $query = "UPDATE {$this->table} SET {$setString} WHERE {$this->primaryKey} = :id";

        return $this->db->execute($query, $params);
    }

    /**
     * Supprime un enregistrement
     */
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->execute($query, ['id' => $id]);
    }

    /**
     * Compte le nombre d'enregistrements
     */
    public function count($conditions = []) {
        $query = "SELECT COUNT(*) as count FROM {$this->table}";

        if (!empty($conditions)) {
            $query .= " WHERE ";
            $whereClauses = [];
            $params = [];

            foreach ($conditions as $key => $value) {
                $whereClauses[] = "{$key} = :{$key}";
                $params[$key] = $value;
            }

            $query .= implode(' AND ', $whereClauses);
            $result = $this->db->selectOne($query, $params);
        } else {
            $result = $this->db->selectOne($query);
        }

        return $result['count'] ?? 0;
    }
}
