<?php
require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json');

try {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		throw new Exception('Méthode non autorisée');
	}

	$username = $_POST['username'] ?? '';
	$password = $_POST['password'] ?? '';

	if (empty($username) || empty($password)) {
		throw new Exception('Nom d\'utilisateur et mot de passe requis');
	}

	$userModel = new User();

	if ($userModel->authenticate($username, $password)) {
		echo json_encode([
			'success' => true,
			'message' => 'Connexion réussie'
		]);
	} else {
		throw new Exception('Identifiants incorrects');
	}

} catch (Exception $e) {
	http_response_code(400);
	echo json_encode([
		'success' => false,
		'message' => $e->getMessage()
	]);
}
