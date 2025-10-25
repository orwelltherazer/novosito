<?php
/**
 * Page Model
 */

namespace App\Models;

use Core\Model;

class Page extends Model {
    protected $table = 'pages';

    /**
     * Récupère une page par son slug
     */
    public function findBySlug($slug) {
        $query = "SELECT * FROM {$this->table} WHERE slug = :slug AND status = 'published'";
        return $this->db->selectOne($query, ['slug' => $slug]);
    }

    /**
     * Récupère les modules d'une page
     */
    public function getModules($pageId) {
        $query = "SELECT * FROM modules WHERE page_id = :page_id AND status = 'active' ORDER BY order_position ASC";
        return $this->db->select($query, ['page_id' => $pageId]);
    }

    /**
     * Récupère la page d'accueil
     */
    public function getHomePage() {
        $query = "SELECT * FROM {$this->table} WHERE is_homepage = 1 AND status = 'published' LIMIT 1";
        return $this->db->selectOne($query);
    }

    /**
     * Récupère toutes les pages publiées
     */
    public function getPublished() {
        $query = "SELECT * FROM {$this->table} WHERE status = 'published' ORDER BY order_position ASC";
        return $this->db->select($query);
    }

    /**
     * Génère un slug unique à partir du titre
     */
    public function generateSlug($title, $excludeId = null) {
        $slug = $this->slugify($title);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Convertit un texte en slug
     */
    private function slugify($text) {
        // Remplace les caractères accentués
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        // Convertit en minuscules
        $text = strtolower($text);
        // Remplace les caractères non alphanumériques par des tirets
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        // Supprime les tirets en début et fin
        $text = trim($text, '-');
        return $text;
    }

    /**
     * Vérifie si un slug existe déjà
     */
    private function slugExists($slug, $excludeId = null) {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE slug = :slug";
        $params = ['slug' => $slug];

        if ($excludeId) {
            $query .= " AND id != :id";
            $params['id'] = $excludeId;
        }

        $result = $this->db->selectOne($query, $params);
        return $result['count'] > 0;
    }
}
