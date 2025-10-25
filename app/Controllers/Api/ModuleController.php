<?php
/**
 * API Module Controller
 * Gère les opérations CRUD sur les modules via l'API
 */

namespace App\Controllers\Api;

use Core\Controller;
use Core\Module;

class ModuleController extends Controller {
    /**
     * Crée un nouveau module
     */
    public function create() {
        $data = $this->getPostData();

        if (empty($data['type']) || empty($data['page_id'])) {
            $this->json(['success' => false, 'message' => 'Données invalides'], 400);
            return;
        }

        // Charge la classe du module
        $moduleClass = $this->getModuleClass($data['type']);

        if (!class_exists($moduleClass)) {
            $this->json(['success' => false, 'message' => 'Module non trouvé'], 404);
            return;
        }

        $module = new $moduleClass([
            'content' => $data['content'] ?? [],
            'settings' => $data['settings'] ?? [],
            'order' => $data['order'] ?? 0
        ]);

        if ($module->save($data['page_id'])) {
            $this->json([
                'success' => true,
                'message' => 'Module créé',
                'module_id' => $module->getId()
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de la création'], 500);
        }
    }

    /**
     * Met à jour un module
     */
    public function update($params) {
        $moduleId = $params['id'] ?? null;
        $data = $this->getPostData();

        if (!$moduleId) {
            $this->json(['success' => false, 'message' => 'ID invalide'], 400);
            return;
        }

        // Charge le module
        $module = Module::load($moduleId);

        if (!$module) {
            $this->json(['success' => false, 'message' => 'Module non trouvé'], 404);
            return;
        }

        // Met à jour les données
        if (isset($data['content'])) {
            $module->setContent($data['content']);
        }

        if (isset($data['settings'])) {
            $module->setSettings($data['settings']);
        }

        // Récupère le page_id
        $moduleData = $this->db->selectOne("SELECT page_id FROM modules WHERE id = :id", ['id' => $moduleId]);

        if ($module->save($moduleData['page_id'])) {
            $this->json(['success' => true, 'message' => 'Module mis à jour']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    /**
     * Supprime un module
     */
    public function delete($params) {
        $moduleId = $params['id'] ?? null;

        if (!$moduleId) {
            $this->json(['success' => false, 'message' => 'ID invalide'], 400);
            return;
        }

        $module = Module::load($moduleId);

        if (!$module) {
            $this->json(['success' => false, 'message' => 'Module non trouvé'], 404);
            return;
        }

        if ($module->delete()) {
            $this->json(['success' => true, 'message' => 'Module supprimé']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de la suppression'], 500);
        }
    }

    /**
     * Réordonne les modules
     */
    public function reorder() {
        $data = $this->getPostData();

        if (empty($data['modules']) || !is_array($data['modules'])) {
            $this->json(['success' => false, 'message' => 'Données invalides'], 400);
            return;
        }

        $this->db->beginTransaction();

        try {
            foreach ($data['modules'] as $order => $moduleId) {
                $this->db->execute(
                    "UPDATE modules SET order_position = :order WHERE id = :id",
                    ['order' => $order, 'id' => $moduleId]
                );
            }

            $this->db->commit();
            $this->json(['success' => true, 'message' => 'Ordre mis à jour']);
        } catch (\Exception $e) {
            $this->db->rollback();
            $this->json(['success' => false, 'message' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    /**
     * Récupère le nom de classe du module
     */
    private function getModuleClass($type) {
        $className = ucfirst($type) . 'Module';
        return "Modules\\{$className}\\{$className}";
    }
}
