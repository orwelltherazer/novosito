<?php
/**
 * Configuration des modules disponibles
 */

return [
    'text' => [
        'name' => 'Texte',
        'description' => 'Bloc de texte avec éditeur WYSIWYG',
        'icon' => 'fas fa-font',
        'class' => 'Modules\Text\TextModule',
        'enabled' => true,
    ],

    'image' => [
        'name' => 'Image',
        'description' => 'Affichage d\'une image unique',
        'icon' => 'fas fa-image',
        'class' => 'Modules\Image\ImageModule',
        'enabled' => true,
    ],

    'gallery' => [
        'name' => 'Galerie',
        'description' => 'Galerie d\'images',
        'icon' => 'fas fa-images',
        'class' => 'Modules\Gallery\GalleryModule',
        'enabled' => true,
    ],

    'contact' => [
        'name' => 'Contact',
        'description' => 'Formulaire de contact',
        'icon' => 'fas fa-envelope',
        'class' => 'Modules\Contact\ContactModule',
        'enabled' => true,
    ],

    'map' => [
        'name' => 'Carte',
        'description' => 'Carte géographique (Google Maps)',
        'icon' => 'fas fa-map-marker-alt',
        'class' => 'Modules\Map\MapModule',
        'enabled' => true,
    ],

    'video' => [
        'name' => 'Vidéo',
        'description' => 'Intégration de vidéos (YouTube, Vimeo)',
        'icon' => 'fas fa-video',
        'class' => 'Modules\Video\VideoModule',
        'enabled' => true,
    ],

    'html' => [
        'name' => 'HTML',
        'description' => 'Code HTML personnalisé',
        'icon' => 'fas fa-code',
        'class' => 'Modules\Html\HtmlModule',
        'enabled' => true,
    ],
];
