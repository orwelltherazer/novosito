<?php
/**
 * Controller Class
 * Contrôleur de base dont héritent tous les contrôleurs
 */

namespace Core;

class Controller {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Charge une vue
     */
    protected function view($viewPath, $data = []) {
        return View::render($viewPath, $data);
    }

    /**
     * Retourne une réponse JSON
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Redirection
     */
    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }

    /**
     * Charge un modèle
     */
    protected function model($modelName) {
        $modelClass = "App\\Models\\{$modelName}";
        if (class_exists($modelClass)) {
            return new $modelClass();
        }
        throw new \Exception("Le modèle {$modelName} n'existe pas");
    }

    /**
     * Vérifie si la requête est AJAX
     */
    protected function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Récupère les données POST
     */
    protected function getPostData() {
        return $_POST;
    }

    /**
     * Récupère les données GET
     */
    protected function getQueryData() {
        return $_GET;
    }

    /**
     * Récupère une valeur POST
     */
    protected function post($key, $default = null) {
        return $_POST[$key] ?? $default;
    }

    /**
     * Récupère une valeur GET
     */
    protected function get($key, $default = null) {
        return $_GET[$key] ?? $default;
    }
}
