<?php

/**
 * Modèle User - Gestion complète des utilisateurs
 * Contient toute la logique métier
 */

require_once __DIR__ . '/../JsonDB.php';

class User
{
	private $db;
	private const FILENAME = 'users.json';

	public function __construct()
	{
		$this->db = JsonDB::getInstance();
	}

	/**
	 * Authentifie un utilisateur et démarre sa session
	 */
	public function authenticate($username, $password): bool
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
	public function getAll()
	{
		return $this->db->read(self::FILENAME);
	}

	/**
	 * Récupère un utilisateur par son ID
	 */
	public function getById($id)
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
	public function getByUsername($username)
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
	public function create($username, $password, $email = null)
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
	public function updatePassword($userId, $newPassword)
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
	public function update($userId, $data)
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
	public function delete($userId)
	{
		$users = $this->db->read(self::FILENAME);

		// Empêcher la suppression si c'est le dernier utilisateur
		if (count($users) <= 1) {
			throw new Exception('Impossible de supprimer le dernier utilisateur');
		}

		$users = array_filter($users, function ($user) use ($userId) {
			return $user['id'] != $userId;
		});

		$this->db->write(self::FILENAME, array_values($users));
		return true;
	}

	/**
	 * Démarre une session utilisateur
	 */
	public function startSession($user)
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
	public function getCurrentUser()
	{
		if (!$this->isLoggedIn()) {
			return null;
		}

		return $this->getById($_SESSION['admin_id']);
	}

	/**
	 * Déconnecte l'utilisateur
	 */
	public function logout()
	{
		session_unset();
		session_destroy();
	}

	/**
	 * Requiert une authentification (redirige vers login si non connecté)
	 */
	public function requireAuth()
	{
		if (!$this->isLoggedIn()) {
			header('Location: login.php');
			exit;
		}
	}

	/**
	 * Génère le prochain ID disponible
	 */
	private function generateNextId($users)
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
	public function format($user)
	{
		return [
			'id' => $user['id'],
			'username' => htmlspecialchars($user['username']),
			'email' => htmlspecialchars($user['email'] ?? ''),
			'created_at' => $user['created_at']
		];
	}
}
