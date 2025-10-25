<?php
/**
 * Home Controller
 * Gère l'affichage de la page d'accueil
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Page;

class HomeController extends Controller {
    /**
     * Affiche la page d'accueil
     */
    public function index() {
        $pageModel = new Page();
        $page = $pageModel->getHomePage();

        if (!$page) {
            http_response_code(404);
            echo "Aucune page d'accueil n'est définie.";
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
