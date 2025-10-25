<?php
/**
 * View Class
 * Gestion des vues et du rendu
 */

namespace Core;

class View {
    /**
     * Rend une vue
     */
    public static function render($viewPath, $data = []) {
        extract($data);

        $viewFile = self::getViewFile($viewPath);

        if (!file_exists($viewFile)) {
            throw new \Exception("La vue {$viewPath} n'existe pas");
        }

        ob_start();
        include $viewFile;
        return ob_get_clean();
    }

    /**
     * Récupère le chemin complet du fichier de vue
     */
    private static function getViewFile($viewPath) {
        // Convertit les points en slashes
        $path = str_replace('.', '/', $viewPath);

        // Vérifie dans les vues du thème actif
        $themeView = __DIR__ . "/../themes/" . self::getActiveTheme() . "/templates/{$path}.php";
        if (file_exists($themeView)) {
            return $themeView;
        }

        // Vérifie dans les vues de l'admin
        $adminView = __DIR__ . "/../admin/views/{$path}.php";
        if (file_exists($adminView)) {
            return $adminView;
        }

        // Vue par défaut
        return __DIR__ . "/../views/{$path}.php";
    }

    /**
     * Récupère le thème actif
     */
    private static function getActiveTheme() {
        $config = require __DIR__ . '/../config/app.php';
        return $config['theme'] ?? 'default';
    }

    /**
     * Échappe les données pour éviter les XSS
     */
    public static function escape($data) {
        if (is_array($data)) {
            return array_map([self::class, 'escape'], $data);
        }
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Inclut un partial
     */
    public static function partial($partialPath, $data = []) {
        extract($data);
        $path = str_replace('.', '/', $partialPath);
        $partialFile = __DIR__ . "/../views/partials/{$path}.php";

        if (file_exists($partialFile)) {
            include $partialFile;
        }
    }

    /**
     * Génère l'URL d'un asset
     */
    public static function asset($path) {
        $baseUrl = self::getBaseUrl();
        return "{$baseUrl}/assets/{$path}";
    }

    /**
     * Récupère l'URL de base
     */
    private static function getBaseUrl() {
        $config = require __DIR__ . '/../config/app.php';
        return $config['url'] ?? '';
    }
}
