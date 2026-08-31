<?php

/**
 * Modèle Bien - Gestion complète des biens immobiliers
 * Contient toute la logique métier
 */

use Osimatic\Data\JsonDB;
use Osimatic\Media\Image;

class Bien
{
	private JsonDB $db;
	private const string FILENAME = 'biens.json';

	public function __construct()
	{
		$this->db = JsonDB::getInstance();
	}

	/**
	 * Récupère tous les biens avec filtres optionnels
	 */
	public function getAll(?string $statut = null, bool $actif = true): array
	{
		$biens = $this->db->read(self::FILENAME);

		// Filtrer par statut
		if ($statut) {
			$biens = array_filter($biens, fn($bien) => $bien['statut'] === $statut);
		}

		// Filtrer par actif
		if ($actif) {
			$biens = array_filter($biens, fn($bien) => $bien['actif'] == 1);
		}

		// Trier par ordre puis par date
		usort($biens, function ($a, $b) {
			if ($a['ordre'] === $b['ordre']) {
				return strtotime($b['created_at']) - strtotime($a['created_at']);
			}
			return $a['ordre'] - $b['ordre'];
		});

		// Ajouter l'URL complète pour chaque image
		foreach ($biens as &$bien) {
			$this->addImageUrls($bien);
		}

		return array_values($biens);
	}

	/**
	 * Récupère un bien par son ID
	 */
	public function getById(int $id, bool $actif = false): ?array
	{
		$biens = $this->getAll(null, $actif);
		foreach ($biens as $bien) {
			if ($bien['id'] == $id) {
				return $bien;
			}
		}
		return null;
	}

	/**
	 * Crée un nouveau bien
	 */
	public function create(array $data): int
	{
		$this->validate($data);

		$biens = $this->db->read(self::FILENAME);

		// Générer un nouvel ID
		$newId = $this->generateNextId($biens);

		$newBien = [
			'id' => $newId,
			'statut' => $data['statut'],
			'titre' => $data['titre'],
			'description' => $data['description'] ?? '',
			'lieu' => $data['lieu'] ?? '',
			'city' => $data['city'] ?? null,
			'surface' => isset($data['surface']) ? floatval($data['surface']) : null,
			'nb_chambres' => isset($data['nb_chambres']) ? intval($data['nb_chambres']) : null,
			'nb_personnes' => isset($data['nb_personnes']) ? intval($data['nb_personnes']) : null,
			'prix' => isset($data['prix']) ? floatval($data['prix']) : null,
			'images' => [],
			'actif' => 1,
			'ordre' => isset($data['ordre']) ? intval($data['ordre']) : 0,
			'id_smoobu' => $data['id_smoobu'] ?? null,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		];

		$biens[] = $newBien;
		$this->db->write(self::FILENAME, $biens);

		return $newId;
	}

	/**
	 * Met à jour un bien
	 */
	public function update(int $id, array $data): bool
	{
		$this->validate($data);

		$biens = $this->db->read(self::FILENAME);
		$updated = false;

		foreach ($biens as &$bien) {
			if ($bien['id'] == $id) {
				$bien['statut'] = $data['statut'];
				$bien['titre'] = $data['titre'];
				$bien['description'] = $data['description'] ?? '';
				$bien['lieu'] = $data['lieu'] ?? '';
				$bien['city'] = $data['city'] ?? null;
				$bien['surface'] = isset($data['surface']) ? floatval($data['surface']) : null;
				$bien['nb_chambres'] = isset($data['nb_chambres']) ? intval($data['nb_chambres']) : null;
				$bien['nb_personnes'] = isset($data['nb_personnes']) ? intval($data['nb_personnes']) : null;
				$bien['prix'] = isset($data['prix']) ? floatval($data['prix']) : null;
				$bien['actif'] = isset($data['actif']) ? intval($data['actif']) : $bien['actif'];
				$bien['ordre'] = isset($data['ordre']) ? intval($data['ordre']) : $bien['ordre'];
				$bien['id_smoobu'] = isset($data['id_smoobu']) ? $data['id_smoobu'] : ($bien['id_smoobu'] ?? null);
				$bien['updated_at'] = date('Y-m-d H:i:s');
				$updated = true;
				break;
			}
		}

		if ($updated) {
			$this->db->write(self::FILENAME, $biens);
		}

		return $updated;
	}

	/**
	 * Supprime un bien et ses images
	 */
	public function delete(int $id): bool
	{
		$biens = $this->db->read(self::FILENAME);
		$bien = $this->getById($id);

		if (!$bien) {
			return false;
		}

		// Supprimer les images physiques
		if (!empty($bien['images'])) {
			foreach ($bien['images'] as $image) {
				$this->deleteImageFile($image['filename']);
			}
		}

		// Supprimer le bien du tableau
		$biens = array_filter($biens, fn ($b) => $b['id'] !== $id);

		$this->db->write(self::FILENAME, array_values($biens));
		return true;
	}

	/**
	 * Ajoute une image à un bien
	 */
	public function addImage(int $bienId, string $filename): bool
	{
		$biens = $this->db->read(self::FILENAME);

		foreach ($biens as &$bien) {
			if ($bien['id'] == $bienId) {
				if (!isset($bien['images'])) {
					$bien['images'] = [];
				}

				$maxOrdre = 0;
				if (!empty($bien['images'])) {
					$ordres = array_column($bien['images'], 'ordre');
					$maxOrdre = max($ordres);
				}

				$bien['images'][] = [
					'filename' => $filename,
					'ordre' => $maxOrdre + 1,
					'created_at' => date('Y-m-d H:i:s')
				];

				$this->db->write(self::FILENAME, $biens);
				return true;
			}
		}

		return false;
	}

	/**
	 * Supprime une image d'un bien
	 */
	public function deleteImage(int $bienId, string $filename): bool
	{
		$biens = $this->db->read(self::FILENAME);

		foreach ($biens as &$bien) {
			if ($bien['id'] == $bienId) {
				// Supprimer le fichier physique
				$this->deleteImageFile($filename);

				// Supprimer de la liste des images
				$bien['images'] = array_filter($bien['images'], fn ($img) => $img['filename'] !== $filename);
				$bien['images'] = array_values($bien['images']);

				$this->db->write(self::FILENAME, $biens);
				return true;
			}
		}

		return false;
	}

	/**
	 * Upload des images
	 */
	public function uploadImages(array $files, int $bienId): array
	{
		$uploadDir = UPLOADS_DIR;

		if (!file_exists($uploadDir)) {
			mkdir($uploadDir, 0755, true);
		}

		$uploadedFiles = [];

		foreach ($files['tmp_name'] as $key => $tmpName) {
			if (empty($tmpName)) {
				continue;
			}

			$originalName = $files['name'][$key];
			$fileSize = $files['size'][$key];
			$fileError = $files['error'][$key];

			if ($fileError !== UPLOAD_ERR_OK || $fileSize > 5 * 1024 * 1024) { // Max 5MB
				continue;
			}

			// Vérifier que le fichier est une image valide (extension + MIME)
			if (!Image::checkFile($tmpName, $originalName)) {
				continue;
			}

			// Générer un nom unique avec une extension basée sur le MIME vérifié (pas le nom original)
			if (null === ($extension = Image::getExtensionFromMimeType(Image::getMimeType($tmpName)))) {
				continue;
			}
			$filename = uniqid('bien_' . $bienId . '_') . '.' . $extension;
			$uploadPath = $uploadDir . $filename;

			if (move_uploaded_file($tmpName, $uploadPath)) {
				$this->addImage($bienId, $filename);
				$uploadedFiles[] = $filename;
			}
		}

		return $uploadedFiles;
	}

	/**
	 * Réordonne les images d'un bien
	 */
	public function reorderImages(int $bienId, array $filenames): bool
	{
		$biens = $this->db->read(self::FILENAME);

		foreach ($biens as &$bien) {
			if ($bien['id'] == $bienId) {
				$indexed = [];
				foreach ($bien['images'] as $img) {
					$indexed[$img['filename']] = $img;
				}

				$reordered = [];
				foreach ($filenames as $i => $filename) {
					if (isset($indexed[$filename])) {
						$indexed[$filename]['ordre'] = $i + 1;
						$reordered[] = $indexed[$filename];
					}
				}

				$bien['images'] = $reordered;
				$this->db->write(self::FILENAME, $biens);
				return true;
			}
		}

		return false;
	}

	/**
	 * Supprime un fichier image physique
	 */
	private function deleteImageFile(string $filename): void
	{
		$imagePath = UPLOADS_DIR . $filename;
		if (file_exists($imagePath)) {
			unlink($imagePath);
		}
	}

	/**
	 * Génère le prochain ID disponible
	 */
	private function generateNextId(array $biens): int
	{
		if (empty($biens)) {
			return 1;
		}
		$ids = array_column($biens, 'id');
		return max($ids) + 1;
	}

	/**
	 * Ajoute l'URL complète à chaque image
	 */
	private function addImageUrls(array &$bien): void
	{
		if (!empty($bien['images'])) {
			foreach ($bien['images'] as &$image) {
				$image['url'] = UPLOADS_PATH . $image['filename'];
			}
		}
	}

	/**
	 * Valide les données d'un bien
	 */
	private function validate(array $data): void
	{
		if (empty($data['statut']) || !in_array($data['statut'], ['vente', 'location'])) {
			throw new Exception('Statut invalide');
		}

		if (empty($data['titre'])) {
			throw new Exception('Le titre est obligatoire');
		}

		if (isset($data['prix']) && $data['prix'] !== '' && $data['prix'] !== null && !is_numeric($data['prix'])) {
			throw new Exception('Le prix doit être un nombre');
		}

		if (isset($data['surface']) && $data['surface'] !== '' && $data['surface'] !== null && !is_numeric($data['surface'])) {
			throw new Exception('La surface doit être un nombre');
		}
	}

	/**
	 * Vérifie qu'un appartement Smoobu existe
	 */
	public function smoobuApartmentExists(int $idSmoobu): bool
	{
		return Booking::getSmoobuClient()->getApartment($idSmoobu) !== null;
	}

	/**
	 * Synchronise les biens locaux avec les appartements Smoobu : sauvegarde biens.json,
	 * puis crée ou met à jour chaque bien correspondant à un appartement Smoobu.
	 * @return array{stats: array{added: int, updated: int, skipped: int, errors: string[]}, total_smoobu: int}
	 * @throws Exception Si les appartements Smoobu ne peuvent pas être récupérés
	 */
	public function synchronizeFromSmoobu(): array
	{
		$this->backup();

		$smoobu = Booking::getSmoobuClient();
		$apartments = $smoobu->getApartments();

		if ($apartments === null) {
			throw new Exception('Impossible de récupérer les appartements depuis Smoobu. Vérifiez votre clé API et secret HMAC.');
		}

		$apartments = $apartments['apartments'] ?? [];

		// Filtrer uniquement les appartements (pas les groupes)
		$apartments = array_filter($apartments, fn($apartmentDetails) => isset($apartmentDetails['id']) && !empty($apartmentDetails['name']));

		// Indexer les biens existants par id_smoobu pour comparaison rapide
		$biensBySmoobuId = [];
		foreach ($this->getAll(null, false) as $bien) {
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

					$this->update($existingBien['id'], $updateData);
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

					$this->create($createData);
					$stats['added']++;
				}
			}
			catch (Exception $e) {
				$stats['errors'][] = "Appartement {$apartment['name']} (ID: {$idSmoobu}): " . $e->getMessage();
				$stats['skipped']++;
			}
		}

		return [
			'stats' => $stats,
			'total_smoobu' => count($apartments),
		];
	}

	/**
	 * Crée une sauvegarde de biens.json
	 */
	public function backup(): string
	{
		$dataDir = $this->db->getDataDirectory();
		$oldDir = $dataDir . '/old';

		// Créer le dossier old s'il n'existe pas
		if (!file_exists($oldDir)) {
			mkdir($oldDir, 0755, true);
		}

		// Générer un nom unique pour la sauvegarde
		$timestamp = date('Ymd_His');
		$uniqueId = uniqid();
		$backupFilename = "biens_{$timestamp}_{$uniqueId}.json";
		$backupPath = $oldDir . '/' . $backupFilename;

		// Copier le fichier
		$sourceFile = $dataDir . '/biens.json';
		if (file_exists($sourceFile)) {
			copy($sourceFile, $backupPath);
		}

		return $backupFilename;
	}

	/**
	 * Formate les données d'un bien pour l'affichage
	 */
	public function format(array $bien): array
	{
		return [
			'id' => $bien['id'],
			'statut' => $bien['statut'],
			'titre' => htmlspecialchars($bien['titre']),
			'description' => htmlspecialchars($bien['description'] ?? ''),
			'lieu' => htmlspecialchars($bien['lieu'] ?? ''),
			'city' => htmlspecialchars($bien['city'] ?? ''),
			'surface' => $bien['surface'] ?? null,
			'nb_chambres' => $bien['nb_chambres'] ?? null,
			'nb_personnes' => $bien['nb_personnes'] ?? null,
			'prix' => $bien['prix'] ?? null,
			'images' => $bien['images'] ?? [],
			'actif' => $bien['actif'],
			'ordre' => $bien['ordre'] ?? 0,
			'created_at' => $bien['created_at'],
			'updated_at' => $bien['updated_at']
		];
	}
}
