<?php require_once 'model/config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Solde réglé — <?= SITE_NAME ?></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css?v=<?= ASSET_TOKEN ?>">
</head>
<body class="d-flex flex-column min-vh-100 justify-content-center align-items-center bg-light">
	<div class="card shadow-sm p-5 text-center" style="max-width: 480px; width: 100%;">
		<div class="mb-4">
			<i class="fa-solid fa-circle-check text-success fa-4x"></i>
		</div>
		<h3 class="mb-2">Solde réglé !</h3>
		<p class="text-muted mb-4">Merci, votre solde a bien été encaissé. Vous allez recevoir une confirmation par email. À bientôt au Capcir !</p>
		<a href="index.php" class="btn btn-sapin mt-2">Retour à l'accueil</a>
	</div>
</body>
</html>
