<?php
// Traitement du formulaire de contact
require_once __DIR__ . '/../model/config.php';

header('Content-Type: application/json; charset=utf-8');

// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
	exit;
}

// Récupérer et nettoyer les données
$nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : '';
$sujet = isset($_POST['sujet']) ? trim($_POST['sujet']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validation des champs obligatoires
$errors = [];

if (empty($nom)) {
	$errors[] = 'Le nom est requis';
}

if (empty($email)) {
	$errors[] = 'L\'email est requis';
}
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$errors[] = 'L\'email n\'est pas valide';
}

if (empty($sujet)) {
	$errors[] = 'Le sujet est requis';
}

if (empty($message)) {
	$errors[] = 'Le message est requis';
}

// Si des erreurs, retourner
if (!empty($errors)) {
	http_response_code(400);
	echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
	exit;
}

// Protection anti-spam simple
if (!empty($_POST['honeypot'])) {
	// Champ honeypot rempli = probablement un bot
	http_response_code(400);
	echo json_encode(['success' => false, 'message' => 'Erreur de validation']);
	exit;
}

// Préparer l'email
$to = EMAIL_DESTINATAIRE;
$subject = 'Nouveau message de contact - ' . $sujet;

// Corps de l'email en HTML
$emailBody = '
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<style>
		body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
		.container { max-width: 600px; margin: 0 auto; padding: 20px; }
		.header { background: #2d5016; color: white; padding: 20px; text-align: center; }
		.content { background: #f8f9fa; padding: 20px; margin: 20px 0; }
		.field { margin-bottom: 15px; }
		.label { font-weight: bold; color: #2d5016; }
		.value { margin-top: 5px; }
		.footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
	</style>
</head>
<body>
	<div class="container">
		<div class="header">
			<h2>Nouveau message de contact</h2>
			<p>' . SITE_NAME . '</p>
		</div>
		<div class="content">
			<div class="field">
				<div class="label">Nom :</div>
				<div class="value">' . htmlspecialchars($nom) . '</div>
			</div>
			<div class="field">
				<div class="label">Email :</div>
				<div class="value"><a href="mailto:' . htmlspecialchars($email) . '">' . htmlspecialchars($email) . '</a></div>
			</div>
			' . (!empty($telephone) ? '
			<div class="field">
				<div class="label">Téléphone :</div>
				<div class="value">' . htmlspecialchars($telephone) . '</div>
			</div>
			' : '') . '
			<div class="field">
				<div class="label">Sujet :</div>
				<div class="value">' . htmlspecialchars($sujet) . '</div>
			</div>
			<div class="field">
				<div class="label">Message :</div>
				<div class="value">' . nl2br(htmlspecialchars($message)) . '</div>
			</div>
		</div>
		<div class="footer">
			<p>Message envoyé depuis le formulaire de contact du site ' . SITE_NAME . '</p>
			<p>Date : ' . date('d/m/Y à H:i') . '</p>
		</div>
	</div>
</body>
</html>
';

// En-têtes de l'email
$headers = [
	'MIME-Version: 1.0',
	'Content-Type: text/html; charset=UTF-8',
	'From: ' . $email,
	'Reply-To: ' . $email,
	'Cc: benoit.guiraudou@gmail.com',
	'X-Mailer: PHP/' . PHP_VERSION
];

// Envoi de l'email
$emailSent = mail($to, $subject, $emailBody, implode("\r\n", $headers));

if (!$emailSent) {
	http_response_code(500);
	echo json_encode([
		'success' => false,
		'message' => 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer plus tard.'
	]);
	exit();
}

echo json_encode([
	'success' => true,
	'message' => 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.'
]);
