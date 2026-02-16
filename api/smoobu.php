<?php
require_once '../model/config.php';

use Osimatic\API\Smoobu;

header('Content-Type: application/json');

$userModel = new User();
//$userModel->requireAuth();

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

			$apartments = $apartments['apartments'] ?? [];

			// Filtrer uniquement les appartements (pas les groupes)
			$apartments = array_filter($apartments, fn($apt) => isset($apartmentDetails['id']) && !empty($apartmentDetails['name']));

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
					// Récupérer les détails complets de l'appartement
					$apartmentDetails = $smoobu->getApartment($idSmoobu);

					if ($apartmentDetails === null) {
						throw new Exception('Impossible de récupérer les détails');
					}

					// Adresse complète
					$postalAddress = \Osimatic\Location\PostalAddress::formatFromComponents(
						countryCode: 'FR',
						city: $apartmentDetails['city'],
						postcode: $apartmentDetails['zip'],
						road: $apartmentDetails['street'],
					);

					// Vérifier si le bien existe déjà
					if (isset($biensBySmoobuId[$idSmoobu])) {
						// Mise à jour
						$existingBien = $biensBySmoobuId[$idSmoobu];

						$updateData = array_merge($existingBien, [
							'statut' => 'location',
							'titre' => $apartmentDetails['name'] ?? '',
							'city' => $apartmentDetails['location']['city'] ?? null,
							'lieu' => $postalAddress,
							'nb_chambres' => $apartmentDetails['rooms']['bedrooms'] ?? null,
							'nb_personnes' => $apartmentDetails['rooms']['maxOccupancy'] ?? null,
							'prix' => $apartmentDetails['price']['minimal'] ?? null,
							'type' => $apartmentDetails['type'] ?? null,
							'id_smoobu' => $idSmoobu
						]);

						$bienModel->update($existingBien['id'], $updateData);
						$stats['updated']++;
					}
					else {
						// Création
						$createData = [
							'statut' => 'location',
							'titre' => $apartmentDetails['name'] ?? '',
							'description' => $apartmentDetails['description'] ?? '',
							'city' => $apartmentDetails['location']['city'] ?? null,
							'lieu' => $postalAddress,
							'surface' => $apartmentDetails['size'] ?? null,
							'nb_chambres' => $apartmentDetails['rooms']['bedrooms'] ?? null,
							'nb_personnes' => $apartmentDetails['rooms']['maxOccupancy'] ?? null,
							'prix' => $apartmentDetails['price']['minimal'] ?? null,
							'type' => $apartmentDetails['type'] ?? null,
							'ordre' => 0,
							'id_smoobu' => $idSmoobu
						];

						$bienModel->create($createData);
						$stats['added']++;
					}
				}
				catch (Exception $e) {
					$stats['errors'][] = "Appartement {$apartment['name']} (ID: {$idSmoobu}): " . $e->getMessage();
					$stats['skipped']++;
				}
			}

			echo json_encode([
				'success' => true,
				'stats' => $stats,
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
