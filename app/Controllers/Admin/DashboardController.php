<?php
/**
 * Admin Dashboard Controller
 * Gère le tableau de bord d'administration
 */

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\Page;
use App\Models\User;

class DashboardController extends Controller {
    /**
     * Affiche le tableau de bord
     */
    public function index() {
        // Vérification de l'authentification
        // TODO: Implémenter un système d'authentification complet

        $pageModel = new Page();
        $stats = [
            'total_pages' => $pageModel->count(),
            'published_pages' => $pageModel->count(['status' => 'published']),
            'draft_pages' => $pageModel->count(['status' => 'draft'])
        ];

        echo $this->view('admin.dashboard', [
            'stats' => $stats
        ]);
    }
}
