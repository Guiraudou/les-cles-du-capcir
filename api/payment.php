<?php
require_once '../model/config.php';

use Osimatic\Security\RateLimiter;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
	exit;
}

$clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (!(new RateLimiter())->check('payment_' . $clientIp, 10, 60)) {
	http_response_code(429);
	echo json_encode(['success' => false, 'error' => 'Trop de requêtes, veuillez réessayer plus tard']);
	exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];

try {
	$booking = Booking::validateBookingRequest($input);
} catch (Exception $e) {
	http_response_code(400);
	echo json_encode(['success' => false, 'error' => $e->getMessage()]);
	exit;
}

try {
	// Le prix n'est jamais accepté depuis le client : il est toujours recalculé ici à partir de Smoobu
	$pricing = Booking::computePrice($booking['apartment_id'], $booking['date_from'], $booking['date_to']);

	if (!$pricing['available']) {
		http_response_code(409);
		echo json_encode(['success' => false, 'error' => 'Ce bien n\'est plus disponible pour ces dates']);
		exit;
	}

	$session = Booking::createCheckoutSession($booking, $pricing);

	echo json_encode([
		'success' => true,
		'session_id' => $session->id,
		'url' => $session->url,
	]);

} catch (\Stripe\Exception\ApiErrorException $e) {
	http_response_code(500);
	echo json_encode(['success' => false, 'error' => 'Erreur Stripe : ' . $e->getMessage()]);
} catch (Exception $e) {
	http_response_code(500);
	echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}