<?php

/**
 * Configuration du site Les clés du Capcir
 */

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/JsonDB.php';
require_once __DIR__ . '/../includes/models/User.php';
require_once __DIR__ . '/../includes/models/Bien.php';

// Initialiser JsonDB avec le chemin des données
JsonDB::initialize(__DIR__ . '/../data');

// Configuration email
define('EMAIL_DESTINATAIRE', 'benoit.guiraudou@gmail.com');

// Informations du site
define('SITE_NAME', 'Les clés du Capcir');
define('SITE_URL', 'https://www.votresite.com');

// Chemins uploads
define('UPLOADS_PATH', 'uploads/biens/');

// Autres paramètres
define('TIMEZONE', 'Europe/Paris');
date_default_timezone_set(TIMEZONE);
