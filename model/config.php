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

// Init secrets
$secrets = parse_ini_file(__DIR__ . '/secrets.ini', true);

// Initialiser JsonDB avec le chemin des données
Osimatic\Data\JsonDB::initialize(__DIR__ . '/../data');

define('ASSET_TOKEN', strtotime('2026-04-29 15:31:00'));

// Contact
define('EMAIL_DESTINATAIRE', $secrets['contact']['email']);
define('PHONE', $secrets['contact']['phone']);

// Informations du site
define('SITE_NAME', 'Les clés du Capcir');
define('SITE_URL', 'https://lesclesducapcir.fr');

// Chemins uploads
define('UPLOADS_PATH', 'data/uploads/biens/'); // Chemin relatif pour l'affichage
define('UPLOADS_DIR', __DIR__ . '/../data/uploads/biens/'); // Chemin absolu pour l'enregistrement
define('MAX_IMAGES_UPLOAD', 10); // Nombre maximum d'images par bien

// Configuration Smoobu
define('SMOOBU_API_KEY', $secrets['smoobu']['api_key']);
define('SMOOBU_ACCOUNT_ID', $secrets['smoobu']['account_id']);

// Autres paramètres
define('TIMEZONE', 'Europe/Paris');
date_default_timezone_set(TIMEZONE);
