<?php

require_once __DIR__ . '/../model/config.php';

use Osimatic\API\Smoobu;

/**
 * API AJAX pour la gestion des biens
 */

// Vérifier l'authentification
$userModel = new User();
if (!$userModel->isLoggedIn()) {
	http_response_code(401);
	echo json_encode(['success' => false, 'message' => 'Non authentifié']);
	exit;
}

header('Content-Type: application/json; charset=utf-8');

$bienModel = new Bien();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

$smoobu = new Smoobu(SMOOBU_API_KEY);

switch ($action) {
	// Récupérer tous les biens
	case 'list':
		$biens = $bienModel->getAll(null, false);
		echo json_encode(['success' => true, 'data' => $biens]);
		break;

	// Récupérer un bien par ID
	case 'get':
		$id = $_GET['id'] ?? null;
		if (!$id) {
			echo json_encode(['success' => false, 'message' => 'ID manquant']);
			break;
		}
		$bien = $bienModel->getById($id);
		if ($bien) {
			echo json_encode(['success' => true, 'data' => $bien]);
		} else {
			echo json_encode(['success' => false, 'message' => 'Bien non trouvé']);
		}
		break;

	// Créer un nouveau bien
	case 'create':
		if (empty($_POST['statut']) || empty($_POST['titre'])) {
			echo json_encode(['success' => false, 'message' => 'Le statut et le titre sont obligatoires']);
			break;
		}

		// Vérifier l'ID Smoobu si fourni
		$idSmoobu = !empty($_POST['id_smoobu']) ? $_POST['id_smoobu'] : null;
		if ($idSmoobu && $_POST['statut'] === 'location') {
			if (null === ($apartment = $smoobu->getApartment((int)$idSmoobu))) {
				echo json_encode(['success' => false, 'message' => 'ID Smoobu invalide ou appartement non trouvé']);
				break;
			}
		}

		try {
			$bienId = $bienModel->create([
				'statut' => $_POST['statut'],
				'titre' => $_POST['titre'],
				'description' => $_POST['description'] ?? '',
				'lieu' => $_POST['lieu'] ?? '',
				'surface' => !empty($_POST['surface']) ? floatval($_POST['surface']) : null,
				'nb_chambres' => !empty($_POST['nb_chambres']) ? intval($_POST['nb_chambres']) : null,
				'nb_personnes' => !empty($_POST['nb_personnes']) ? intval($_POST['nb_personnes']) : null,
				'prix' => !empty($_POST['prix']) ? floatval($_POST['prix']) : null,
				'ordre' => isset($_POST['ordre']) ? intval($_POST['ordre']) : 0,
				'id_smoobu' => $idSmoobu
			]);

			// Upload des images
			$uploadedCount = 0;
			if (!empty($_FILES['images']['name'][0])) {
				$uploadedFiles = $bienModel->uploadImages($_FILES['images'], $bienId);
				$uploadedCount = count($uploadedFiles);
			}

			echo json_encode([
				'success' => true,
				'message' => 'Bien créé avec succès',
				'id' => $bienId,
				'images_uploaded' => $uploadedCount
			]);
		} catch (Exception $e) {
			echo json_encode(['success' => false, 'message' => $e->getMessage()]);
		}
		break;

	// Mettre à jour un bien
	case 'update':
		$id = $_POST['id'] ?? null;
		if (!$id) {
			echo json_encode(['success' => false, 'message' => 'ID manquant']);
			break;
		}

		if (empty($_POST['statut']) || empty($_POST['titre'])) {
			echo json_encode(['success' => false, 'message' => 'Le statut et le titre sont obligatoires']);
			break;
		}

		// Vérifier l'ID Smoobu si fourni
		$idSmoobu = !empty($_POST['id_smoobu']) ? $_POST['id_smoobu'] : null;
		if ($idSmoobu && $_POST['statut'] === 'location') {
			if (null === ($apartment = $smoobu->getApartment((int)$idSmoobu))) {
				echo json_encode(['success' => false, 'message' => 'ID Smoobu invalide ou appartement non trouvé']);
				break;
			}
		}

		try {
			$updated = $bienModel->update($id, [
				'statut' => $_POST['statut'],
				'titre' => $_POST['titre'],
				'description' => $_POST['description'] ?? '',
				'lieu' => $_POST['lieu'] ?? '',
				'surface' => !empty($_POST['surface']) ? floatval($_POST['surface']) : null,
				'nb_chambres' => !empty($_POST['nb_chambres']) ? intval($_POST['nb_chambres']) : null,
				'nb_personnes' => !empty($_POST['nb_personnes']) ? intval($_POST['nb_personnes']) : null,
				'prix' => !empty($_POST['prix']) ? floatval($_POST['prix']) : null,
				'actif' => isset($_POST['actif']) ? intval($_POST['actif']) : 1,
				'ordre' => isset($_POST['ordre']) ? intval($_POST['ordre']) : 0,
				'id_smoobu' => $idSmoobu
			]);

			// Upload de nouvelles images
			$uploadedCount = 0;
			if (!empty($_FILES['images']['name'][0])) {
				$uploadedFiles = $bienModel->uploadImages($_FILES['images'], $id);
				$uploadedCount = count($uploadedFiles);
			}

			if ($updated) {
				echo json_encode([
					'success' => true,
					'message' => 'Bien mis à jour avec succès',
					'images_uploaded' => $uploadedCount
				]);
			} else {
				echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
			}
		} catch (Exception $e) {
			echo json_encode(['success' => false, 'message' => $e->getMessage()]);
		}
		break;

	// Supprimer un bien
	case 'delete':
		$id = $_POST['id'] ?? $_GET['id'] ?? null;
		if (!$id) {
			echo json_encode(['success' => false, 'message' => 'ID manquant']);
			break;
		}

		$deleted = $bienModel->delete($id);
		if ($deleted) {
			echo json_encode(['success' => true, 'message' => 'Bien supprimé avec succès']);
		} else {
			echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
		}
		break;

	// Supprimer une image
	case 'delete-image':
		$bienId = $_POST['bien_id'] ?? null;
		$filename = $_POST['filename'] ?? null;

		if (!$bienId || !$filename) {
			echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
			break;
		}

		$deleted = $bienModel->deleteImage($bienId, $filename);
		if ($deleted) {
			echo json_encode(['success' => true, 'message' => 'Image supprimée']);
		} else {
			echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression de l\'image']);
		}
		break;

	default:
		http_response_code(400);
		echo json_encode(['success' => false, 'message' => 'Action invalide']);
		break;
}