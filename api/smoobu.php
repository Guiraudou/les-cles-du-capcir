<?php
require_once '../model/config.php';

use Osimatic\Calendar\SqlDate;

header('Content-Type: application/json');

$userModel = new User();
//$userModel->requireAuth();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
	$smoobu = Booking::getSmoobuClient();

	switch ($action) {
		case 'get':
			$apartmentId = $_GET['id'] ?? '';

			if (empty($apartmentId) || !is_numeric($apartmentId)) {
				throw new Exception('ID Smoobu invalide');
			}

			$apartment = $smoobu->getApartment((int)$apartmentId);

			if ($apartment === null) {
				throw new Exception('Appartement Smoobu non trouvé');
			}

			echo json_encode([
				'success' => true,
				'data' => $apartment
			]);
			break;

		case 'verify':
			$apartmentId = $_GET['id'] ?? '';

			if (empty($apartmentId) || !is_numeric($apartmentId)) {
				throw new Exception('ID Smoobu invalide');
			}

			$apartment = $smoobu->getApartment((int)$apartmentId);

			echo json_encode([
				'success' => true,
				'exists' => $apartment !== null
			]);
			break;

		case 'synchronize':
			$bienModel = new Bien();
			$result = $bienModel->synchronizeFromSmoobu();

			echo json_encode(array_merge(['success' => true], $result));
			break;

		case 'rates':
			$apartmentId = $_GET['id'] ?? '';
			$startDate = $_GET['start_date'] ?? date('Y-m-d');

			if (empty($apartmentId) || !is_numeric($apartmentId)) {
				throw new Exception('ID Smoobu invalide');
			}
			if (!SqlDate::isValid($startDate) || $startDate < date('Y-m-d')) {
				throw new Exception('Date de début invalide');
			}

			$rates = $smoobu->getRates([
				'apartments' => [(int)$apartmentId],
				'start_date' => $startDate,
				'end_date' => date('Y-m-d', strtotime($startDate . ' +2 months')),
			]);

			echo json_encode([
				'success' => true,
				'data' => $rates
			]);
			break;

		default:
			throw new Exception('Action non valide');
	}

} catch (Exception $e) {
	http_response_code(400);
	echo json_encode([
		'success' => false,
		'error' => $e->getMessage()
	]);
}