<?php
require_once '../model/config.php';

use Osimatic\API\Smoobu;

header('Content-Type: application/json');

$userModel = new User();
$userModel->requireAuth();

$action = $_GET['action'] ?? '';

try {
	$smoobu = new Smoobu(SMOOBU_API_KEY);

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

			// Convertir les données Smoobu en format application
			$converted = convertSmoobuData($apartment);

			echo json_encode([
				'success' => true,
				'data' => $converted
			]);
			break;

		case 'verify':
			$apartmentId = $_GET['id'] ?? '';

			if (empty($apartmentId) || !is_numeric($apartmentId)) {
				throw new Exception('ID Smoobu invalide');
			}

			$apartment = $smoobu->getApartment((int)$apartmentId);

			echo json_encode([
				'success' => $apartment !== null,
				'exists' => $apartment !== null
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

/**
 * Convertit les données Smoobu en format de l'application
 */
function convertSmoobuData($smoobuData) {
	$converted = [];

	// Titre
	if (isset($smoobuData['name'])) {
		$converted['titre'] = $smoobuData['name'];
	}

	// Description
	if (isset($smoobuData['description'])) {
		$converted['description'] = $smoobuData['description'];
	}

	// Lieu (adresse)
	if (isset($smoobuData['address'])) {
		$address = $smoobuData['address'];
		$lieu = [];
		if (!empty($address['street'])) $lieu[] = $address['street'];
		if (!empty($address['zipcode'])) $lieu[] = $address['zipcode'];
		if (!empty($address['city'])) $lieu[] = $address['city'];
		if (!empty($lieu)) {
			$converted['lieu'] = implode(', ', $lieu);
		}
	}

	// Surface
	if (isset($smoobuData['size'])) {
		$converted['surface'] = $smoobuData['size'];
	}

	// Chambres
	if (isset($smoobuData['bedrooms'])) {
		$converted['nb_chambres'] = $smoobuData['bedrooms'];
	}

	// Personnes
	if (isset($smoobuData['maxOccupancy'])) {
		$converted['nb_personnes'] = $smoobuData['maxOccupancy'];
	}

	// Prix (prix de base)
	if (isset($smoobuData['prices']['basePrice'])) {
		$converted['prix'] = $smoobuData['prices']['basePrice'];
	} elseif (isset($smoobuData['defaultPrice'])) {
		$converted['prix'] = $smoobuData['defaultPrice'];
	}

	// URLs des images
	if (isset($smoobuData['pictures']) && is_array($smoobuData['pictures'])) {
		$converted['images_urls'] = array_map(function($img) {
			return $img['url'] ?? $img['large'] ?? $img['medium'] ?? '';
		}, $smoobuData['pictures']);

		// Filtrer les URLs vides
		$converted['images_urls'] = array_filter($converted['images_urls']);
		$converted['images_urls'] = array_values($converted['images_urls']);
	}

	return $converted;
}
