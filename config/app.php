<?php
/**
 * Configuration générale de l'application
 */

return [
    // Nom de l'application
    'name' => 'NovoSito CMS',

    // URL de base
    'url' => 'http://localhost/novosito',

    // Thème actif
    'theme' => 'default',

    // Langue par défaut
    'locale' => 'fr',

    // Fuseau horaire
    'timezone' => 'Europe/Paris',

    // Debug mode
    'debug' => true,

    // Uploads
    'uploads' => [
        'max_size' => 5242880, // 5MB en octets
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
        'images_path' => 'uploads/images',
        'documents_path' => 'uploads/documents',
    ],

    // Pagination
    'pagination' => [
        'per_page' => 10,
    ],

    // Sécurité
    'security' => [
        'session_lifetime' => 7200, // 2 heures
        'password_min_length' => 8,
    ],
];
