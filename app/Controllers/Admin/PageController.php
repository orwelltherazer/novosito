<?php
/**
 * Admin Page Controller
 * Gère la gestion des pages dans l'administration
 */

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\Page;

class PageController extends Controller {
    /**
     * Liste toutes les pages
     */
    public function index() {
        $pageModel = new Page();
        $pages = $pageModel->all('order_position ASC');

        echo $this->view('admin.pages.index', [
            'pages' => $pages
        ]);
    }

    /**
     * Affiche le formulaire de création
     */
    public function create() {
        echo $this->view('admin.pages.create');
    }

    /**
     * Enregistre une nouvelle page
     */
    public function store() {
        $data = $this->getPostData();

        $pageModel = new Page();

        // Génère le slug
        $slug = $pageModel->generateSlug($data['title']);

        // Prépare les données
        $pageData = [
            'site_id' => 1,
            'title' => $data['title'],
            'slug' => $slug,
            'meta_title' => $data['meta_title'] ?? $data['title'],
            'meta_description' => $data['meta_description'] ?? '',
            'template' => $data['template'] ?? 'default',
            'status' => $data['status'] ?? 'draft',
            'is_homepage' => isset($data['is_homepage']) ? 1 : 0,
            'published_at' => ($data['status'] ?? 'draft') === 'published' ? date('Y-m-d H:i:s') : null
        ];

        $pageId = $pageModel->create($pageData);

        if ($pageId) {
            $this->redirect('/admin/pages/' . $pageId . '/edit');
        } else {
            // TODO: Gérer l'erreur
            echo "Erreur lors de la création de la page";
        }
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit($params) {
        $pageId = $params['id'] ?? null;

        if (!$pageId) {
            $this->redirect('/admin/pages');
            return;
        }

        $pageModel = new Page();
        $page = $pageModel->find($pageId);

        if (!$page) {
            $this->redirect('/admin/pages');
            return;
        }

        // Récupère les modules de la page
        $modules = $pageModel->getModules($pageId);

        // Récupère la configuration des modules disponibles
        $availableModules = require __DIR__ . '/../../../config/modules.php';

        echo $this->view('admin.pages.edit', [
            'page' => $page,
            'modules' => $modules,
            'availableModules' => $availableModules
        ]);
    }

    /**
     * Met à jour une page
     */
    public function update($params) {
        $pageId = $params['id'] ?? null;
        $data = $this->getPostData();

        if (!$pageId) {
            $this->json(['success' => false, 'message' => 'ID de page invalide'], 400);
            return;
        }

        $pageModel = new Page();

        // Prépare les données
        $pageData = [
            'title' => $data['title'],
            'meta_title' => $data['meta_title'] ?? $data['title'],
            'meta_description' => $data['meta_description'] ?? '',
            'template' => $data['template'] ?? 'default',
            'status' => $data['status'] ?? 'draft',
            'is_homepage' => isset($data['is_homepage']) ? 1 : 0
        ];

        // Met à jour le slug si le titre a changé
        $currentPage = $pageModel->find($pageId);
        if ($currentPage['title'] !== $data['title']) {
            $pageData['slug'] = $pageModel->generateSlug($data['title'], $pageId);
        }

        // Met à jour la date de publication si nécessaire
        if ($pageData['status'] === 'published' && !$currentPage['published_at']) {
            $pageData['published_at'] = date('Y-m-d H:i:s');
        }

        if ($pageModel->update($pageId, $pageData)) {
            if ($this->isAjax()) {
                $this->json(['success' => true, 'message' => 'Page mise à jour']);
            } else {
                $this->redirect('/admin/pages/' . $pageId . '/edit');
            }
        } else {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Erreur lors de la mise à jour'], 500);
            } else {
                echo "Erreur lors de la mise à jour";
            }
        }
    }

    /**
     * Supprime une page
     */
    public function delete($params) {
        $pageId = $params['id'] ?? null;

        if (!$pageId) {
            $this->json(['success' => false, 'message' => 'ID de page invalide'], 400);
            return;
        }

        $pageModel = new Page();

        if ($pageModel->delete($pageId)) {
            $this->json(['success' => true, 'message' => 'Page supprimée']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de la suppression'], 500);
        }
    }
}
