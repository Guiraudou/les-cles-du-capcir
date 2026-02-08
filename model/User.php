<?php

/**
 * Modèle User - Gestion complète des utilisateurs
 * Contient toute la logique métier
 */

use Osimatic\Data\JsonDB;

class User
{
	private JsonDB $db;
	private const string FILENAME = 'users.json';

	public function __construct()
	{
		$this->db = JsonDB::getInstance();
	}

	/**
	 * Authentifie un utilisateur et démarre sa session
	 */
	public function authenticate(string $username, string $password): bool
	{
		$user = $this->getByUsername($username);

		if (!$user) {
			return false;
		}

		if (!password_verify($password, $user['password'])) {
			return false;
		}

		$this->startSession($user);
		return true;
	}

	/**
	 * Récupère tous les utilisateurs
	 */
	public function getAll(): array
	{
		return $this->db->read(self::FILENAME);
	}

	/**
	 * Récupère un utilisateur par son ID
	 */
	public function getById(int $id): ?array
	{
		$users = $this->db->read(self::FILENAME);
		foreach ($users as $user) {
			if ($user['id'] == $id) {
				return $user;
			}
		}
		return null;
	}

	/**
	 * Récupère un utilisateur par son username
	 */
	public function getByUsername(string $username): ?array
	{
		$users = $this->db->read(self::FILENAME);
		foreach ($users as $user) {
			if ($user['username'] === $username) {
				return $user;
			}
		}
		return null;
	}

	/**
	 * Crée un nouvel utilisateur
	 */
	public function create(string $username, string $password, ?string $email = null): int
	{
		// Vérifier si l'utilisateur existe déjà
		if ($this->getByUsername($username)) {
			throw new Exception('Cet utilisateur existe déjà');
		}

		// Valider le mot de passe
		if (strlen($password) < 6) {
			throw new Exception('Le mot de passe doit contenir au moins 6 caractères');
		}

		$users = $this->db->read(self::FILENAME);

		// Générer un nouvel ID
		$newId = $this->generateNextId($users);

		$newUser = [
			'id' => $newId,
			'username' => $username,
			'password' => password_hash($password, PASSWORD_DEFAULT),
			'email' => $email ?? '',
			'created_at' => date('Y-m-d H:i:s')
		];

		$users[] = $newUser;
		$this->db->write(self::FILENAME, $users);

		return $newId;
	}

	/**
	 * Met à jour le mot de passe d'un utilisateur
	 */
	public function updatePassword(int $userId, string $newPassword): bool
	{
		if (strlen($newPassword) < 6) {
			throw new Exception('Le mot de passe doit contenir au moins 6 caractères');
		}

		$users = $this->db->read(self::FILENAME);
		$updated = false;

		foreach ($users as &$user) {
			if ($user['id'] == $userId) {
				$user['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
				$updated = true;
				break;
			}
		}

		if ($updated) {
			$this->db->write(self::FILENAME, $users);
		}

		return $updated;
	}

	/**
	 * Met à jour les informations d'un utilisateur
	 */
	public function update(int $userId, array $data): bool
	{
		$users = $this->db->read(self::FILENAME);
		$updated = false;

		foreach ($users as &$user) {
			if ($user['id'] == $userId) {
				if (isset($data['username'])) {
					$user['username'] = $data['username'];
				}
				if (isset($data['email'])) {
					$user['email'] = $data['email'];
				}
				$updated = true;
				break;
			}
		}

		if ($updated) {
			$this->db->write(self::FILENAME, $users);
		}

		return $updated;
	}

	/**
	 * Supprime un utilisateur
	 */
	public function delete(int $userId): bool
	{
		$users = $this->db->read(self::FILENAME);

		// Empêcher la suppression si c'est le dernier utilisateur
		if (count($users) <= 1) {
			throw new Exception('Impossible de supprimer le dernier utilisateur');
		}

		$users = array_filter($users, fn ($user) => $user['id'] !== $userId);

		$this->db->write(self::FILENAME, array_values($users));

		return true;
	}

	/**
	 * Démarre une session utilisateur
	 */
	public function startSession(array $user): void
	{
		$_SESSION['admin_id'] = $user['id'];
		$_SESSION['admin_username'] = $user['username'];
		$_SESSION['admin_email'] = $user['email'] ?? '';
	}

	/**
	 * Vérifie si un utilisateur est connecté
	 */
	public function isLoggedIn(): bool
	{
		return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
	}

	/**
	 * Récupère l'utilisateur actuellement connecté
	 */
	public function getCurrentUser(): ?array
	{
		if (!$this->isLoggedIn()) {
			return null;
		}

		return $this->getById($_SESSION['admin_id']);
	}

	/**
	 * Déconnecte l'utilisateur
	 */
	public function logout(): void
	{
		session_unset();
		session_destroy();
	}

	/**
	 * Requiert une authentification (redirige vers login si non connecté)
	 */
	public function requireAuth(): void
	{
		if (!$this->isLoggedIn()) {
			header('Location: login.php');
			exit;
		}
	}

	/**
	 * Génère le prochain ID disponible
	 */
	private function generateNextId($users): int
	{
		if (empty($users)) {
			return 1;
		}
		$ids = array_column($users, 'id');
		return max($ids) + 1;
	}

	/**
	 * Formate les données d'un utilisateur pour l'affichage (sans le mot de passe)
	 */
	public function format($user): array
	{
		return [
			'id' => $user['id'],
			'username' => htmlspecialchars($user['username']),
			'email' => htmlspecialchars($user['email'] ?? ''),
			'created_at' => $user['created_at']
		];
	}
}
