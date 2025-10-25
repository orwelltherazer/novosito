<?php
/**
 * Media Model
 */

namespace App\Models;

use Core\Model;

class Media extends Model {
    protected $table = 'media';

    /**
     * Récupère les médias d'un type spécifique
     */
    public function getByType($type) {
        $query = "SELECT * FROM {$this->table} WHERE type = :type ORDER BY created_at DESC";
        return $this->db->select($query, ['type' => $type]);
    }

    /**
     * Récupère les images
     */
    public function getImages() {
        return $this->getByType('image');
    }

    /**
     * Upload un média
     */
    public function upload($file, $userId = null) {
        $uploadDir = __DIR__ . '/../../uploads/';
        $config = require __DIR__ . '/../../config/app.php';

        // Vérifie la taille du fichier
        if ($file['size'] > $config['uploads']['max_size']) {
            throw new \Exception('Le fichier est trop volumineux');
        }

        // Vérifie le type de fichier
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $config['uploads']['allowed_types'])) {
            throw new \Exception('Type de fichier non autorisé');
        }

        // Détermine le type de média
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $type = in_array($extension, $imageExtensions) ? 'image' : 'document';

        // Génère un nom unique
        $filename = uniqid() . '_' . time() . '.' . $extension;

        // Détermine le répertoire de destination
        $subDir = $type === 'image' ? 'images/' : 'documents/';
        $targetDir = $uploadDir . $subDir;

        // Crée le répertoire s'il n'existe pas
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetPath = $targetDir . $filename;

        // Déplace le fichier
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Récupère les dimensions pour les images
            $width = null;
            $height = null;
            if ($type === 'image') {
                list($width, $height) = getimagesize($targetPath);
            }

            // Enregistre en base de données
            return $this->create([
                'user_id' => $userId,
                'filename' => $filename,
                'original_filename' => $file['name'],
                'file_path' => $subDir . $filename,
                'mime_type' => $file['type'],
                'file_size' => $file['size'],
                'width' => $width,
                'height' => $height,
                'type' => $type
            ]);
        }

        throw new \Exception('Erreur lors de l\'upload du fichier');
    }

    /**
     * Supprime un média
     */
    public function deleteMedia($id) {
        $media = $this->find($id);

        if ($media) {
            $filePath = __DIR__ . '/../../uploads/' . $media['file_path'];

            // Supprime le fichier physique
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Supprime l'enregistrement en base
            return $this->delete($id);
        }

        return false;
    }
}
