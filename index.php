<?php
/**
 * NovoSito CMS
 * Point d'entrée principal de l'application
 */

// Démarrage de la session
session_start();

// Définition des constantes
define('ROOT_PATH', __DIR__);
define('CORE_PATH', ROOT_PATH . '/core');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('MODULES_PATH', ROOT_PATH . '/modules');
define('THEMES_PATH', ROOT_PATH . '/themes');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// Chargement de l'autoloader
require_once CORE_PATH . '/Autoloader.php';
Core\Autoloader::register();

// Gestion des erreurs en mode debug
$config = require CONFIG_PATH . '/app.php';
if ($config['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Configuration du fuseau horaire
date_default_timezone_set($config['timezone']);

// Création du routeur
$router = new Core\Router();

// Routes publiques
$router->get('/', function() {
    $controller = new App\Controllers\HomeController();
    return $controller->index();
});

$router->get('/page/{slug}', function($params) {
    $controller = new App\Controllers\PageController();
    return $controller->show($params);
});

// Routes d'administration
$router->get('/admin', function() {
    $controller = new App\Controllers\Admin\DashboardController();
    return $controller->index();
});

$router->get('/admin/pages', function() {
    $controller = new App\Controllers\Admin\PageController();
    return $controller->index();
});

$router->get('/admin/pages/create', function() {
    $controller = new App\Controllers\Admin\PageController();
    return $controller->create();
});

$router->post('/admin/pages', function() {
    $controller = new App\Controllers\Admin\PageController();
    return $controller->store();
});

$router->get('/admin/pages/{id}/edit', function($params) {
    $controller = new App\Controllers\Admin\PageController();
    return $controller->edit($params);
});

$router->post('/admin/pages/{id}', function($params) {
    $controller = new App\Controllers\Admin\PageController();
    return $controller->update($params);
});

$router->delete('/admin/pages/{id}', function($params) {
    $controller = new App\Controllers\Admin\PageController();
    return $controller->delete($params);
});

// Routes API pour les modules
$router->post('/api/modules', function() {
    $controller = new App\Controllers\Api\ModuleController();
    return $controller->create();
});

$router->put('/api/modules/{id}', function($params) {
    $controller = new App\Controllers\Api\ModuleController();
    return $controller->update($params);
});

$router->delete('/api/modules/{id}', function($params) {
    $controller = new App\Controllers\Api\ModuleController();
    return $controller->delete($params);
});

$router->post('/api/modules/reorder', function() {
    $controller = new App\Controllers\Api\ModuleController();
    return $controller->reorder();
});

// Routes API pour les médias
$router->post('/api/media/upload', function() {
    $controller = new App\Controllers\Api\MediaController();
    return $controller->upload();
});

$router->get('/api/media', function() {
    $controller = new App\Controllers\Api\MediaController();
    return $controller->index();
});

$router->delete('/api/media/{id}', function($params) {
    $controller = new App\Controllers\Api\MediaController();
    return $controller->delete($params);
});

// Dispatch de la requête
try {
    $router->dispatch();
} catch (Exception $e) {
    if ($config['debug']) {
        echo "<h1>Erreur</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        http_response_code(500);
        echo "Une erreur est survenue.";
    }
}
