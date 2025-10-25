<?php
/**
 * Gallery Module
 * Module de galerie d'images
 */

namespace Modules\Gallery;

use Core\Module;

class GalleryModule extends Module {
    protected $type = 'gallery';
    protected $name = 'Galerie';

    /**
     * Rend le module
     */
    public function render() {
        $images = $this->content['images'] ?? [];
        $columns = $this->settings['columns'] ?? 3;
        $spacing = $this->settings['spacing'] ?? '10px';

        if (empty($images)) {
            return '';
        }

        $html = "<div class='module module-gallery' data-module-id='{$this->id}'>";
        $html .= "<div class='gallery-grid' style='display: grid; grid-template-columns: repeat({$columns}, 1fr); gap: {$spacing};'>";

        foreach ($images as $image) {
            $imageUrl = $image['url'] ?? '';
            $altText = $image['alt'] ?? '';
            $caption = $image['caption'] ?? '';

            $html .= "<div class='gallery-item'>";
            $html .= "<img src='{$imageUrl}' alt='{$altText}' class='img-fluid' style='width: 100%; height: auto; object-fit: cover;'>";
            if (!empty($caption)) {
                $html .= "<p class='gallery-caption'>{$caption}</p>";
            }
            $html .= "</div>";
        }

        $html .= "</div></div>";

        return $html;
    }

    /**
     * Rend le formulaire d'édition
     */
    public function renderEditForm() {
        $images = $this->content['images'] ?? [];
        $columns = $this->settings['columns'] ?? 3;
        $spacing = $this->settings['spacing'] ?? '10px';

        $imagesJson = htmlspecialchars(json_encode($images), ENT_QUOTES, 'UTF-8');

        return '
        <div class="form-group">
            <label>Images</label>
            <input type="hidden" name="content[images]" id="gallery-images" value=\'' . $imagesJson . '\'>
            <button type="button" class="btn btn-secondary" onclick="openGalleryManager()">Gérer les images</button>
            <div id="gallery-preview" class="mt-3">
                <!-- Les images seront affichées ici via JavaScript -->
            </div>
        </div>
        <div class="form-group">
            <label>Nombre de colonnes</label>
            <select name="settings[columns]" class="form-control">
                <option value="2"' . ($columns == 2 ? ' selected' : '') . '>2 colonnes</option>
                <option value="3"' . ($columns == 3 ? ' selected' : '') . '>3 colonnes</option>
                <option value="4"' . ($columns == 4 ? ' selected' : '') . '>4 colonnes</option>
                <option value="5"' . ($columns == 5 ? ' selected' : '') . '>5 colonnes</option>
            </select>
        </div>
        <div class="form-group">
            <label>Espacement</label>
            <input type="text" name="settings[spacing]" class="form-control" value="' . $spacing . '" placeholder="Ex: 10px">
        </div>
        <script>
        // Afficher les images de la galerie
        function displayGalleryImages() {
            const imagesInput = document.getElementById("gallery-images");
            const preview = document.getElementById("gallery-preview");
            const images = JSON.parse(imagesInput.value || "[]");

            preview.innerHTML = "";
            images.forEach((img, index) => {
                const div = document.createElement("div");
                div.className = "d-inline-block m-2";
                div.innerHTML = `
                    <img src="${img.url}" style="width: 100px; height: 100px; object-fit: cover;">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeGalleryImage(${index})">Supprimer</button>
                `;
                preview.appendChild(div);
            });
        }

        // Supprimer une image
        function removeGalleryImage(index) {
            const imagesInput = document.getElementById("gallery-images");
            const images = JSON.parse(imagesInput.value || "[]");
            images.splice(index, 1);
            imagesInput.value = JSON.stringify(images);
            displayGalleryImages();
        }

        // Afficher les images au chargement
        setTimeout(displayGalleryImages, 100);
        </script>';
    }

    /**
     * Valide les données
     */
    public function validate($data) {
        if (empty($data['content']['images']) || !is_array($data['content']['images'])) {
            return false;
        }
        return true;
    }
}
