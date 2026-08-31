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
require_once __DIR__ . '/../model/Booking.php';
require_once __DIR__ . '/../model/Mailer.php';

// Init secrets
$secrets = parse_ini_file(__DIR__ . '/secrets.ini', true);

// Initialiser JsonDB avec le chemin des données
Osimatic\Data\JsonDB::initialize(__DIR__ . '/../data');

define('ASSET_TOKEN', strtotime('2026-04-29 15:31:00'));

// Contact
define('SENDER_EMAIL', $secrets['contact']['sender_email']);
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
define('SMOOBU_API_SECRET', $secrets['smoobu']['api_secret']);
define('SMOOBU_ACCOUNT_ID', $secrets['smoobu']['account_id']);
define('SMOOBU_CHANNEL_ID_DIRECT', 1852); // Canal "Direct" Smoobu, utilisé pour les réservations créées depuis le site

// Configuration Stripe
define('STRIPE_SECRET_KEY', $secrets['stripe']['secret_key']);
define('STRIPE_WEBHOOK_SECRET', $secrets['stripe']['webhook_secret']);

// Tunnel de réservation en ligne
define('BOOKING_MARKUP_RATE', 0.05); // Majoration appliquée au tarif Smoobu pour le paiement en ligne (+5%)

// Autres paramètres
define('TIMEZONE', 'Europe/Paris');
date_default_timezone_set(TIMEZONE);
