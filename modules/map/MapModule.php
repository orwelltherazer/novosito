<?php
/**
 * Map Module
 * Module de carte géographique
 */

namespace Modules\Map;

use Core\Module;

class MapModule extends Module {
    protected $type = 'map';
    protected $name = 'Carte';

    /**
     * Rend le module
     */
    public function render() {
        $address = $this->content['address'] ?? '';
        $latitude = $this->content['latitude'] ?? '';
        $longitude = $this->content['longitude'] ?? '';
        $zoom = $this->settings['zoom'] ?? 15;
        $height = $this->settings['height'] ?? '400px';
        $mapId = 'map-' . $this->id;

        if (empty($address) && (empty($latitude) || empty($longitude))) {
            return '';
        }

        // Si on a une adresse mais pas de coordonnées, on utilise l'API de geocoding
        $displayAddress = !empty($address) ? urlencode($address) : "{$latitude},{$longitude}";

        $html = "<div class='module module-map' data-module-id='{$this->id}'>";
        $html .= "<div id='{$mapId}' style='width: 100%; height: {$height};'></div>";

        // Utilisation de Leaflet (OpenStreetMap) - gratuit et sans clé API
        $html .= "
        <link rel='stylesheet' href='https://unpkg.com/leaflet@1.9.4/dist/leaflet.css' />
        <script src='https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'></script>
        <script>
        (function() {
            var lat = {$latitude} || 48.8566;
            var lng = {$longitude} || 2.3522;

            var map = L.map('{$mapId}').setView([lat, lng], {$zoom});

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map)
                .bindPopup('" . addslashes($address) . "');
        })();
        </script>";

        $html .= "</div>";

        return $html;
    }

    /**
     * Rend le formulaire d'édition
     */
    public function renderEditForm() {
        $address = $this->content['address'] ?? '';
        $latitude = $this->content['latitude'] ?? '';
        $longitude = $this->content['longitude'] ?? '';
        $zoom = $this->settings['zoom'] ?? 15;
        $height = $this->settings['height'] ?? '400px';

        return '
        <div class="form-group">
            <label>Adresse</label>
            <input type="text" name="content[address]" class="form-control" value="' . htmlspecialchars($address) . '" placeholder="Ex: 1 Rue de Rivoli, 75001 Paris">
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Latitude</label>
                    <input type="text" name="content[latitude]" class="form-control" value="' . htmlspecialchars($latitude) . '" placeholder="Ex: 48.8566">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Longitude</label>
                    <input type="text" name="content[longitude]" class="form-control" value="' . htmlspecialchars($longitude) . '" placeholder="Ex: 2.3522">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Niveau de zoom</label>
            <input type="range" name="settings[zoom]" class="form-control-range" min="1" max="18" value="' . $zoom . '" oninput="this.nextElementSibling.value = this.value">
            <output>' . $zoom . '</output>
        </div>
        <div class="form-group">
            <label>Hauteur de la carte</label>
            <input type="text" name="settings[height]" class="form-control" value="' . $height . '" placeholder="Ex: 400px">
        </div>';
    }

    /**
     * Valide les données
     */
    public function validate($data) {
        if (empty($data['content']['address']) && (empty($data['content']['latitude']) || empty($data['content']['longitude']))) {
            return false;
        }
        return true;
    }
}
