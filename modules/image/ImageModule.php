<?php
/**
 * Image Module
 * Module d'affichage d'image
 */

namespace Modules\Image;

use Core\Module;

class ImageModule extends Module {
    protected $type = 'image';
    protected $name = 'Image';

    /**
     * Rend le module
     */
    public function render() {
        $imageUrl = $this->content['image_url'] ?? '';
        $altText = $this->content['alt_text'] ?? '';
        $caption = $this->content['caption'] ?? '';
        $alignment = $this->settings['alignment'] ?? 'center';
        $width = $this->settings['width'] ?? '100%';
        $link = $this->content['link'] ?? '';

        if (empty($imageUrl)) {
            return '';
        }

        $alignClass = 'text-' . $alignment;
        $imgStyle = "max-width: {$width}; height: auto;";

        $imageHtml = "<img src='{$imageUrl}' alt='{$altText}' style='{$imgStyle}' class='img-fluid'>";

        if (!empty($link)) {
            $imageHtml = "<a href='{$link}'>{$imageHtml}</a>";
        }

        $html = "<div class='module module-image {$alignClass}' data-module-id='{$this->id}'>";
        $html .= $imageHtml;

        if (!empty($caption)) {
            $html .= "<p class='image-caption'>{$caption}</p>";
        }

        $html .= "</div>";

        return $html;
    }

    /**
     * Rend le formulaire d'édition
     */
    public function renderEditForm() {
        $imageUrl = $this->content['image_url'] ?? '';
        $altText = $this->content['alt_text'] ?? '';
        $caption = $this->content['caption'] ?? '';
        $link = $this->content['link'] ?? '';
        $alignment = $this->settings['alignment'] ?? 'center';
        $width = $this->settings['width'] ?? '100%';

        return '
        <div class="form-group">
            <label>Image</label>
            <div class="image-selector">
                <input type="text" name="content[image_url]" class="form-control" value="' . htmlspecialchars($imageUrl) . '" id="image-url-input">
                <button type="button" class="btn btn-secondary mt-2" onclick="openMediaLibrary()">Choisir une image</button>
            </div>
            ' . (!empty($imageUrl) ? '<div class="mt-2"><img src="' . $imageUrl . '" style="max-width: 200px; height: auto;"></div>' : '') . '
        </div>
        <div class="form-group">
            <label>Texte alternatif (alt)</label>
            <input type="text" name="content[alt_text]" class="form-control" value="' . htmlspecialchars($altText) . '">
        </div>
        <div class="form-group">
            <label>Légende</label>
            <input type="text" name="content[caption]" class="form-control" value="' . htmlspecialchars($caption) . '">
        </div>
        <div class="form-group">
            <label>Lien (optionnel)</label>
            <input type="url" name="content[link]" class="form-control" value="' . htmlspecialchars($link) . '">
        </div>
        <div class="form-group">
            <label>Alignement</label>
            <select name="settings[alignment]" class="form-control">
                <option value="left"' . ($alignment === 'left' ? ' selected' : '') . '>Gauche</option>
                <option value="center"' . ($alignment === 'center' ? ' selected' : '') . '>Centre</option>
                <option value="right"' . ($alignment === 'right' ? ' selected' : '') . '>Droite</option>
            </select>
        </div>
        <div class="form-group">
            <label>Largeur</label>
            <input type="text" name="settings[width]" class="form-control" value="' . $width . '" placeholder="Ex: 100%, 500px">
        </div>';
    }

    /**
     * Valide les données
     */
    public function validate($data) {
        if (empty($data['content']['image_url'])) {
            return false;
        }
        return true;
    }
}
