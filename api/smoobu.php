<?php
require_once '../model/config.php';

use Osimatic\API\Smoobu;

header('Content-Type: application/json');

$userModel = new User();
$userModel->requireAuth();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

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

		case 'synchronize':
			// 1. Faire une sauvegarde de biens.json
			$bienModel = new Bien();
			$backupPath = $bienModel->backup();

			// 2. Récupérer tous les appartements depuis Smoobu
			$apartments = $smoobu->getApartments();

			if ($apartments === null) {
				throw new Exception('Impossible de récupérer les appartements depuis Smoobu. Vérifiez votre clé API.');
			}

			// Filtrer uniquement les appartements (pas les groupes)
			$apartments = array_filter($apartments, fn($apt) => isset($apt['id']) && !empty($apt['name']));

			// 3. Récupérer tous les biens existants
			$existingBiens = $bienModel->getAll(null, false);

			// Indexer par id_smoobu pour comparaison rapide
			$biensBySmoobuId = [];
			foreach ($existingBiens as $bien) {
				if (!empty($bien['id_smoobu'])) {
					$biensBySmoobuId[$bien['id_smoobu']] = $bien;
				}
			}

			$stats = [
				'added' => 0,
				'updated' => 0,
				'skipped' => 0,
				'errors' => []
			];

			// 4. Synchroniser chaque appartement
			foreach ($apartments as $apartment) {
				$idSmoobu = $apartment['id'];

				try {
					// Convertir les données Smoobu
					$convertedData = convertSmoobuData($apartment);

					// Vérifier si le bien existe déjà
					if (isset($biensBySmoobuId[$idSmoobu])) {
						// Mise à jour
						$existingBien = $biensBySmoobuId[$idSmoobu];

						$updateData = [
							'statut' => 'location',
							'titre' => $convertedData['titre'],
							'description' => $convertedData['description'] ?? '',
							'lieu' => $convertedData['lieu'] ?? '',
							'surface' => $convertedData['surface'] ?? null,
							'nb_chambres' => $convertedData['nb_chambres'] ?? null,
							'nb_personnes' => $convertedData['nb_personnes'] ?? null,
							'prix' => $convertedData['prix'] ?? null,
							'actif' => $existingBien['actif'], // Garder le statut actif actuel
							'ordre' => $existingBien['ordre'], // Garder l'ordre actuel
							'id_smoobu' => $idSmoobu
						];

						$bienModel->update($existingBien['id'], $updateData);
						$stats['updated']++;
					} else {
						// Création
						$createData = [
							'statut' => 'location',
							'titre' => $convertedData['titre'],
							'description' => $convertedData['description'] ?? '',
							'lieu' => $convertedData['lieu'] ?? '',
							'surface' => $convertedData['surface'] ?? null,
							'nb_chambres' => $convertedData['nb_chambres'] ?? null,
							'nb_personnes' => $convertedData['nb_personnes'] ?? null,
							'prix' => $convertedData['prix'] ?? null,
							'ordre' => 0,
							'id_smoobu' => $idSmoobu
						];

						$bienModel->create($createData);
						$stats['added']++;
					}
				} catch (Exception $e) {
					$stats['errors'][] = "Appartement {$apartment['name']} (ID: {$idSmoobu}): " . $e->getMessage();
					$stats['skipped']++;
				}
			}

			echo json_encode([
				'success' => true,
				'message' => "Synchronisation terminée : {$stats['added']} ajouté(s), {$stats['updated']} mis à jour, {$stats['skipped']} ignoré(s)",
				'stats' => $stats,
				'backup' => $backupPath,
				'total_smoobu' => count($apartments)
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
