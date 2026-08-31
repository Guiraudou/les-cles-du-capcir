<?php

require_once __DIR__ . '/../model/config.php';

/**
 * API AJAX pour la gestion des biens
 */

header('Content-Type: application/json; charset=utf-8');

$userModel = new User();
$isAuthenticated = $userModel->isLoggedIn();

$bienModel = new Bien();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Actions publiques (sans authentification)
$publicActions = ['list', 'get'];

// Vérifier l'authentification pour les actions non publiques
if (!in_array($action, $publicActions) && !$isAuthenticated) {
	http_response_code(401);
	echo json_encode(['success' => false, 'message' => 'Non authentifié']);
	exit;
}

// Détecter si la requête a été tronquée par les limites PHP
if (empty($action) && $_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) {
	http_response_code(413);
	echo json_encode([
		'success' => false,
		'message' => 'La requête est trop volumineuse. Réduisez le nombre ou la taille des images.',
		'hint' => 'Limites PHP: post_max_size=' . ini_get('post_max_size') . ', upload_max_filesize=' . ini_get('upload_max_filesize')
	]);
	exit;
}

switch ($action) {
	// Récupérer tous les biens
	case 'list':
		// Si authentifié (admin), retourner tous les biens. Sinon, uniquement les biens actifs
		$biens = $bienModel->getAll(null, !$isAuthenticated);
		echo json_encode(['success' => true, 'data' => $biens]);
		break;

	// Récupérer un bien par ID
	case 'get':
		$id = $_GET['id'] ?? null;
		if (!$id) {
			echo json_encode(['success' => false, 'message' => 'ID manquant']);
			break;
		}
		// Si non authentifié, filtrer uniquement les biens actifs
		$bien = $bienModel->getById($id, !$isAuthenticated);
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
			if (!$bienModel->smoobuApartmentExists((int)$idSmoobu)) {
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
				'city' => $_POST['city'] ?? '',
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
		}
		catch (Exception $e) {
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
			if (!$bienModel->smoobuApartmentExists((int)$idSmoobu)) {
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
				'city' => $_POST['city'] ?? '',
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

	// Réordonner les images
	case 'reorder-images':
		$bienId = $_POST['bien_id'] ?? null;
		$filenames = $_POST['filenames'] ?? [];

		if (!$bienId || empty($filenames)) {
			echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
			break;
		}

		$reordered = $bienModel->reorderImages((int)$bienId, $filenames);
		echo json_encode($reordered ? ['success' => true] : ['success' => false, 'message' => 'Erreur lors du réordonnancement']);
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
		echo json_encode([
			'success' => false,
			'message' => 'Action invalide',
			'action_received' => $action,
			'post_data_exists' => !empty($_POST),
			'hint' => empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST' ? 'La requête POST est vide, vérifiez les limites PHP' : ''
		]);
		break;
}