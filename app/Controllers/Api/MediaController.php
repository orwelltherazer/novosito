<?php
/**
 * API Media Controller
 * Gère les opérations sur les médias via l'API
 */

namespace App\Controllers\Api;

use Core\Controller;
use App\Models\Media;

class MediaController extends Controller {
    /**
     * Liste les médias
     */
    public function index() {
        $type = $this->get('type', 'image');

        $mediaModel = new Media();
        $media = $type === 'all' ? $mediaModel->all('created_at DESC') : $mediaModel->getByType($type);

        $this->json([
            'success' => true,
            'media' => $media
        ]);
    }

    /**
     * Upload un média
     */
    public function upload() {
        if (empty($_FILES['file'])) {
            $this->json(['success' => false, 'message' => 'Aucun fichier'], 400);
            return;
        }

        $file = $_FILES['file'];
        $userId = $_SESSION['user_id'] ?? null;

        try {
            $mediaModel = new Media();
            $mediaId = $mediaModel->upload($file, $userId);

            if ($mediaId) {
                $media = $mediaModel->find($mediaId);
                $this->json([
                    'success' => true,
                    'message' => 'Fichier uploadé',
                    'media' => $media
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'Erreur lors de l\'upload'], 500);
            }
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Supprime un média
     */
    public function delete($params) {
        $mediaId = $params['id'] ?? null;

        if (!$mediaId) {
            $this->json(['success' => false, 'message' => 'ID invalide'], 400);
            return;
        }

        $mediaModel = new Media();

        if ($mediaModel->deleteMedia($mediaId)) {
            $this->json(['success' => true, 'message' => 'Média supprimé']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de la suppression'], 500);
        }
    }
}
