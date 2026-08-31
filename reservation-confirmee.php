<?php
require_once 'model/config.php';

$sessionId = $_GET['session_id'] ?? '';
$session = null;
$error = false;

if ($sessionId) {
	try {
		\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
		$session = \Stripe\Checkout\Session::retrieve($sessionId);
	} catch (Exception $e) {
		$error = true;
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Réservation confirmée — <?= SITE_NAME ?></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css?v=<?= ASSET_TOKEN ?>">
</head>
<body class="d-flex flex-column min-vh-100 justify-content-center align-items-center bg-light">
	<div class="card shadow-sm p-5 text-center" style="max-width: 520px; width: 100%;">
		<?php if ($error || !$session || $session->payment_status !== 'paid'): ?>
			<div class="mb-4">
				<i class="fa-solid fa-circle-exclamation text-warning fa-4x"></i>
			</div>
			<h3 class="mb-3">Session introuvable</h3>
			<p class="text-muted">Nous n'avons pas pu retrouver les détails de votre réservation. Si vous avez bien reçu un email de confirmation, tout s'est bien passé !</p>
		<?php else: ?>
			<div class="mb-4">
				<i class="fa-solid fa-circle-check text-success fa-4x"></i>
			</div>
			<h3 class="mb-2">Réservation confirmée !</h3>
			<p class="text-muted mb-4">
				Merci <strong><?= htmlspecialchars($session->metadata->guest_name ?? '') ?></strong>,
				votre réservation pour <strong><?= htmlspecialchars($session->metadata->titre ?? 'votre séjour') ?></strong>
				du <strong><?= htmlspecialchars($session->metadata->date_from ?? '') ?></strong>
				au <strong><?= htmlspecialchars($session->metadata->date_to ?? '') ?></strong> est bien enregistrée.
			</p>
			<p class="text-muted small">Un email de confirmation vous a été envoyé à <strong><?= htmlspecialchars($session->customer_email ?? '') ?></strong>.</p>
			<hr>
			<p class="text-muted small">Montant réglé : <strong><?= number_format(($session->amount_total ?? 0) / 100, 2, ',', ' ') ?> €</strong></p>
		<?php endif; ?>
		<a href="location.php" class="btn btn-sapin mt-3">Retour aux locations</a>
	</div>
</body>
</html>