<?php
/**
 * Page Controller
 * Gère l'affichage des pages
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Page;

class PageController extends Controller {
    /**
     * Affiche une page par son slug
     */
    public function show($params) {
        $slug = $params['slug'] ?? '';

        $pageModel = new Page();
        $page = $pageModel->findBySlug($slug);

        if (!$page) {
            http_response_code(404);
            echo "Page non trouvée.";
            return;
        }

        // Récupère les modules de la page
        $modules = $pageModel->getModules($page['id']);

        // Rend la vue
        echo $this->view('page', [
            'page' => $page,
            'modules' => $modules
        ]);
    }
}
