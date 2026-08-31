<?php
/**
 * api/reservation.php
 * Appelé par le webhook Stripe après un paiement réussi.
 * Crée la réservation dans Smoobu et envoie un email de confirmation.
 */
require_once '../model/config.php';

// Ce endpoint est appelé en webhook Stripe — pas de header JSON ici
$payload = file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

$result = Booking::confirmStripePayment($payload, $sigHeader);

http_response_code($result['status']);
echo $result['body'];