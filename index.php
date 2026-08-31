<?php require_once 'model/config.php'; ?>
<?php
$meta_title = 'Vente immobilière & Conciergerie au Capcir | ' . SITE_NAME;
$meta_description = 'Les Clés du Capcir : vente immobilière et conciergerie locative au cœur du Capcir. Chalets, appartements, gestion de location saisonnière. Contactez-nous.';
?>
<?php require_once 'header.inc.php'; ?>

<!-- HERO (with integrated menu) -->
<header id="top" class="hero">

	<!-- Logo en haut à gauche -->
	<div class="hero-logo-corner">
		<img src="images/logo.png" alt="Les Clés du Capcir">
	</div>

	<!-- integrated menu in hero -->
	<div class="hero-nav">
		<div class="container d-flex align-items-center justify-content-between">
			<div class="brand"></div>

			<div class="d-none d-lg-flex align-items-center gap-4">
				<a class="nav-link" href="#top">Accueil</a>
				<a class="nav-link" href="location.php">Location</a>
				<a class="nav-link" href="vente.php">Vente</a>
				<a class="nav-link" href="tarif.php">Tarifs</a>
				<a class="nav-link" href="#contact">Contact</a>
				<a class="btn-ghost" href="location.php">Réserver</a>
			</div>

			<!-- mobile toggle hamburger -->
			<button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#heroMenu" aria-controls="heroMenu" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
		</div>

		<!-- Menu mobile collapsible -->
		<div class="collapse navbar-collapse d-lg-none" id="heroMenu">
			<div class="container">
				<ul class="navbar-nav py-3 gap-2">
					<li class="nav-item"><a class="nav-link" href="#top">Accueil</a></li>
					<li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
					<li class="nav-item"><a class="nav-link" href="#biens">Biens</a></li>
					<li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
					<li class="nav-item mt-2">
						<a class="btn-ghost" href="location.php">Réserver</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-xl-9 hero-content">
				<div class="brand-title mb-2">
					Les Clés du Capcir
				</div>
				<h1 class="display-5 fw-bold mb-4">
					Vente & Conciergerie<br>
					Au cœur du Capcir
				</h1>
				<div class="d-flex flex-wrap gap-3 align-items-center pt-3">
					<a href="location.php" class="btn btn-sapin">Réserver maintenant</a>
					<a href="vente.php" class="btn btn-outline-sapin">Biens en vente</a>
					<a href="#contact" class="btn btn-link-sapin btn-contact-arrow">
						Nous contacter →
					</a>
				</div>

			</div>
		</div>
	</div>
</header>

<!-- SERVICES -->
<section id="services" class="section-services">
	<div class="container">
		<div class="row g-4">
			<div class="col-6 col-lg-3">
				<div class="service-card">
					<div class="service-box">
					<div class="service-icon" aria-hidden="true">
						<i class="fa-solid fa-house"></i>
					</div>
					<div>
						<div class="fw-bold fs-5">Vente immobilière</div>
						<div class="text-muted small">Trouvez votre bien</div>
					</div>
					</div>
				</div>
			</div>

			<div class="col-6 col-lg-3">
				<div class="service-card">
					<div class="service-box">
					<div class="service-icon" aria-hidden="true">
						<i class="fa-solid fa-calendar-check"></i>
					</div>
					<div>
						<div class="fw-bold fs-5">Conciergerie</div>
						<div class="text-muted small">Gestion complète</div>
					</div>
					</div>
				</div>
			</div>

			<div class="col-6 col-lg-3">
				<div class="service-card">
					<div class="service-box">
					<div class="service-icon" aria-hidden="true">
						<i class="fa-solid fa-headset"></i>
					</div>
					<div>
						<div class="fw-bold fs-5">Assistance</div>
						<div class="text-muted small">7j/7</div>
					</div>
					</div>
				</div>
			</div>

			<div class="col-6 col-lg-3">
				<div class="service-card">
					<div class="service-box">
					<div class="service-icon" aria-hidden="true">
						<i class="fa-solid fa-shirt"></i>
					</div>
					<div>
						<div class="fw-bold fs-5">Location de linge</div>
						<div class="text-muted small">Impeccable & soigné</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- 2 COLUMNS: VENTES + LOCATIONS -->
<section id="biens" class="section-biens">
	<div class="container">
		<div class="row g-4 biens-row">
			<!-- VENTES -->
			<div class="col-lg-6">
				<div class="vente-col">
					<div class="d-flex align-items-center justify-content-between mb-4">
						<h2 class="section-title mb-0">Ventes</h2>
						<a href="vente.php" class="text-decoration-none link-voir-tout">Tout voir →</a>
					</div>

					<div id="ventes-list" class="row g-3">
						<!-- Les biens en vente seront chargés ici en JavaScript -->
					</div>
				</div>
			</div>

			<!-- LOCATIONS -->
			<div class="col-lg-6">
				<div class="location-col">
					<div class="d-flex align-items-center justify-content-between mb-4">
						<h2 class="section-title mb-0">Locations</h2>
						<a href="location.php" class="text-decoration-none link-voir-tout">Tout voir →</a>
					</div>

					<div id="locations-list" class="row g-3">
						<!-- Les biens en location seront chargés ici en JavaScript -->
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<!-- CONTACT -->
<section id="contact" class="section-contact">
	<div class="container">
		<h2 class="section-title mb-4 text-center">Contact</h2>
		<?php include 'contact.inc.php'; ?>
	</div>
</section>

<!-- FOOTER -->

<?php include 'booking.inc.php'; ?>
<?php include 'bien.inc.php'; ?>

<?php require_once 'footer.inc.php'; ?>