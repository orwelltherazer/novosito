<?php
/**
 * Autoloader Class
 * Chargement automatique des classes
 */

namespace Core;

class Autoloader {
    /**
     * Enregistre l'autoloader
     */
    public static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /**
     * Charge automatiquement les classes
     */
    private static function autoload($className) {
        // Convertit les namespaces en chemin de fichier
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);

        // Chemins à vérifier
        $paths = [
            __DIR__ . '/../',
            __DIR__ . '/../app/',
        ];

        foreach ($paths as $path) {
            $file = $path . $className . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
}
