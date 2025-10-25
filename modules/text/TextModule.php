<?php
/**
 * Text Module
 * Module de texte avec éditeur WYSIWYG
 */

namespace Modules\Text;

use Core\Module;

class TextModule extends Module {
    protected $type = 'text';
    protected $name = 'Texte';

    /**
     * Rend le module
     */
    public function render() {
        $text = $this->content['text'] ?? '';
        $alignment = $this->settings['alignment'] ?? 'left';
        $backgroundColor = $this->settings['background_color'] ?? 'transparent';
        $padding = $this->settings['padding'] ?? '20px';

        $style = "text-align: {$alignment}; background-color: {$backgroundColor}; padding: {$padding};";

        return "<div class='module module-text' data-module-id='{$this->id}' style='{$style}'>{$text}</div>";
    }

    /**
     * Rend le formulaire d'édition
     */
    public function renderEditForm() {
        $text = $this->content['text'] ?? '';
        $alignment = $this->settings['alignment'] ?? 'left';
        $backgroundColor = $this->settings['background_color'] ?? '#ffffff';
        $padding = $this->settings['padding'] ?? '20px';

        return '
        <div class="form-group">
            <label>Contenu</label>
            <textarea name="content[text]" class="wysiwyg-editor form-control" rows="10">' . htmlspecialchars($text) . '</textarea>
        </div>
        <div class="form-group">
            <label>Alignement</label>
            <select name="settings[alignment]" class="form-control">
                <option value="left"' . ($alignment === 'left' ? ' selected' : '') . '>Gauche</option>
                <option value="center"' . ($alignment === 'center' ? ' selected' : '') . '>Centre</option>
                <option value="right"' . ($alignment === 'right' ? ' selected' : '') . '>Droite</option>
                <option value="justify"' . ($alignment === 'justify' ? ' selected' : '') . '>Justifié</option>
            </select>
        </div>
        <div class="form-group">
            <label>Couleur de fond</label>
            <input type="color" name="settings[background_color]" class="form-control" value="' . $backgroundColor . '">
        </div>
        <div class="form-group">
            <label>Espacement interne (padding)</label>
            <input type="text" name="settings[padding]" class="form-control" value="' . $padding . '" placeholder="Ex: 20px">
        </div>';
    }

    /**
     * Valide les données
     */
    public function validate($data) {
        if (empty($data['content']['text'])) {
            return false;
        }
        return true;
    }
}
