<?php
/**
 * Router Class
 * Système de routage pour le CMS
 */

namespace Core;

class Router {
    private $routes = [];
    private $params = [];

    /**
     * Ajoute une route GET
     */
    public function get($path, $callback) {
        $this->addRoute('GET', $path, $callback);
    }

    /**
     * Ajoute une route POST
     */
    public function post($path, $callback) {
        $this->addRoute('POST', $path, $callback);
    }

    /**
     * Ajoute une route PUT
     */
    public function put($path, $callback) {
        $this->addRoute('PUT', $path, $callback);
    }

    /**
     * Ajoute une route DELETE
     */
    public function delete($path, $callback) {
        $this->addRoute('DELETE', $path, $callback);
    }

    /**
     * Ajoute une route
     */
    private function addRoute($method, $path, $callback) {
        $path = $this->normalizePath($path);
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    /**
     * Normalise le chemin
     */
    private function normalizePath($path) {
        $path = trim($path, '/');
        $path = "/{$path}/";
        $path = preg_replace('#[/]{2,}#', '/', $path);
        return $path;
    }

    /**
     * Dispatch la requête
     */
    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];

        // Retire les paramètres de requête
        $requestUri = strtok($requestUri, '?');
        $requestUri = $this->normalizePath($requestUri);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            if ($this->matchRoute($route['path'], $requestUri)) {
                return $this->executeCallback($route['callback']);
            }
        }

        // Route non trouvée
        http_response_code(404);
        echo "404 - Page non trouvée";
    }

    /**
     * Vérifie si la route correspond
     */
    private function matchRoute($routePath, $requestUri) {
        // Convertit les paramètres dynamiques en regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestUri, $matches)) {
            // Extrait les paramètres
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $this->params[$key] = $value;
                }
            }
            return true;
        }

        return false;
    }

    /**
     * Exécute le callback
     */
    private function executeCallback($callback) {
        if (is_callable($callback)) {
            return call_user_func_array($callback, [$this->params]);
        }

        if (is_string($callback)) {
            $parts = explode('@', $callback);
            if (count($parts) === 2) {
                $controller = $parts[0];
                $method = $parts[1];

                if (class_exists($controller)) {
                    $instance = new $controller();
                    if (method_exists($instance, $method)) {
                        return call_user_func_array([$instance, $method], [$this->params]);
                    }
                }
            }
        }

        throw new \Exception("Callback invalide pour la route");
    }

    /**
     * Retourne les paramètres de la route
     */
    public function getParams() {
        return $this->params;
    }
}
