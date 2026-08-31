<?php
require_once '../model/config.php';

use Osimatic\Security\RateLimiter;

header('Content-Type: application/json');

$clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (!(new RateLimiter())->check('availability_' . $clientIp, 20, 60)) {
	http_response_code(429);
	echo json_encode(['success' => false, 'error' => 'Trop de requêtes, veuillez réessayer plus tard']);
	exit;
}

$apartmentId = $_GET['apartment_id'] ?? '';
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';

try {
	$booking = Booking::validateAvailabilityRequest($apartmentId, $dateFrom, $dateTo);
} catch (Exception $e) {
	http_response_code(400);
	echo json_encode(['success' => false, 'error' => $e->getMessage()]);
	exit;
}

try {
	$pricing = Booking::computePrice($booking['apartment_id'], $booking['date_from'], $booking['date_to']);

	if (!$pricing['available']) {
		echo json_encode([
			'success' => true,
			'available' => false,
			'message' => 'Ce bien n\'est pas disponible pour les dates sélectionnées'
		]);
		exit;
	}

	echo json_encode(array_merge([
		'success' => true,
		'date_from' => $booking['date_from'],
		'date_to' => $booking['date_to'],
		'apartment_id' => $booking['apartment_id'],
	], $pricing));

} catch (Exception $e) {
	http_response_code(500);
	echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}