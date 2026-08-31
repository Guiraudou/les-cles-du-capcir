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

if (empty($email) && empty($telephone)) {
	$errors[] = 'Un email ou un téléphone est requis';
}
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$errors[] = 'L\'email n\'est pas valide';
}
if (!empty($telephone) && !\Osimatic\Messaging\PhoneNumber::isValid($telephone)) {
	$errors[] = 'Le numéro de téléphone n\'est pas valide';
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

// Corps de l'email en HTML (gabarit commun model/email/header.inc.php + footer.inc.php)
$bodyHtml = Mailer::field('Nom', $nom);
if (!empty($email)) {
	$bodyHtml .= Mailer::field('Email', '<a href="mailto:' . htmlspecialchars($email) . '">' . htmlspecialchars($email) . '</a>', true);
}
if (!empty($telephone)) {
	$bodyHtml .= Mailer::field('Téléphone', $telephone);
}
$bodyHtml .= Mailer::field('Sujet', $sujet);
$bodyHtml .= Mailer::field('Message', $message);

$emailBody = Mailer::renderTemplate(
	'Nouveau message de contact',
	$bodyHtml,
	'Message envoyé depuis le formulaire de contact du site ' . SITE_NAME
);

// Reply-To uniquement si le visiteur a renseigné un email ; copie systématique au propriétaire
$emailSent = Mailer::send($to, $subject, $emailBody, $email ?: null, $nom, ['benoit.guiraudou@gmail.com']);

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
