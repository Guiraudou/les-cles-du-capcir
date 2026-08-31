<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?= isset($meta_title) ? htmlspecialchars($meta_title) : htmlspecialchars(SITE_NAME . ' — Vente & Conciergerie au Capcir') ?></title>
	<?php if (!empty($meta_description)): ?>
	<meta name="description" content="<?= htmlspecialchars($meta_description) ?>">
	<?php endif; ?>
	<link rel="icon" type="image/png" href="images/logo.png">

	<!-- Bootstrap 5 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<link rel="stylesheet" href="style.css?<?= ASSET_TOKEN; ?>">

	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,600;1,400&display=swap" rel="stylesheet">

	<!-- Font Awesome 6 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-5X228J3D');</script>
	<!-- End Google Tag Manager -->
</head>

<body>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5X228J3D"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php
// Détecter le type de page
$currentPage = basename($_SERVER['PHP_SELF']);
$isAdmin = ($currentPage === 'admin.php');
$isIndex = ($currentPage === 'index.php');
?>

<?php if ($isAdmin): ?>
	<!-- Navbar Admin -->
	<nav class="navbar navbar-dark mb-4">
		<div class="container-fluid">
			<span class="navbar-brand">
				<i class="fa-solid fa-key"></i> Administration - Les clés du Capcir
			</span>
			<div class="d-flex gap-2">
				<span class="navbar-text text-white me-3">
					<i class="fa-solid fa-user"></i> <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>
				</span>
				<a href="logout.php" class="btn btn-outline-light btn-sm d-flex align-items-center gap-2">
					<i class="fa-solid fa-right-from-bracket"></i>
					<span>Déconnexion</span>
				</a>
			</div>
		</div>
	</nav>
<?php else: ?>
	<!-- Sticky header (public) -->
	<nav id="stickyNav" class="navbar navbar-expand-lg sticky-nav <?= !$isIndex ? 'always-visible' : '' ?>">
		<div class="container py-1">
			<a class="navbar-brand" href="index.php">
				<img src="images/logo.png" alt="Les clés du Capcir">
				Les clés du Capcir
			</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#stickyMenu">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="stickyMenu">
				<ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
					<li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
					<li class="nav-item"><a class="nav-link" href="location.php">Location</a></li>
					<li class="nav-item"><a class="nav-link" href="vente.php">Vente</a></li>
					<li class="nav-item"><a class="nav-link" href="tarif.php">Tarifs</a></li>
					<li class="nav-item"><a class="nav-link" href="index.php#contact">Contact</a></li>
					<li class="nav-item ms-lg-2">
						<a class="btn btn-sapin" href="location.php">Réserver</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
<?php endif; ?>