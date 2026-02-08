<?php

/**
 * Configuration du site Les clés du Capcir
 */

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__).'/vendor/autoload.php';

require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Bien.php';

// Initialiser JsonDB avec le chemin des données
Osimatic\Data\JsonDB::initialize(__DIR__ . '/../data');

// Configuration email
define('EMAIL_DESTINATAIRE', 'benoit.guiraudou@gmail.com');

// Informations du site
define('SITE_NAME', 'Les clés du Capcir');
define('SITE_URL', 'https://www.votresite.com');

// Chemins uploads
define('UPLOADS_PATH', 'uploads/biens/');

// Configuration Smoobu
define('SMOOBU_API_KEY', ''); // À renseigner avec votre clé API Smoobu
define('SMOOBU_ACCOUNT_ID', '1436716');

// Autres paramètres
define('TIMEZONE', 'Europe/Paris');
date_default_timezone_set(TIMEZONE);
