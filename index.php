<?php
require_once 'model/config.php';

// Charger les biens depuis la base de données
$bienModel = new Bien();
$allBiens = $bienModel->getAll();

// Filtrer et limiter les biens actifs
$ventes = array_slice(array_filter($allBiens, fn ($b) => $b['statut'] === 'vente' && $b['actif']), 0, 3);
$locations = array_slice(array_filter($allBiens, fn ($b) => $b['statut'] === 'location' && $b['actif']), 0, 3);

?>
<?php require_once 'header.inc.php'; ?>

<!-- HERO (with integrated menu) -->
<header id="top" class="hero">

	<!-- Logo en haut à gauche -->
	<div class="hero-logo-corner">
		<img src="images/logo.png" alt="Les clés du Capcir">
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
				<a class="btn-ghost" href="#" data-bs-toggle="modal" data-bs-target="#modal_booking">Réserver</a>
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
						<a class="btn-ghost" href="#" data-bs-toggle="modal" data-bs-target="#modal_booking">Réserver</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-xl-9 hero-content">
				<div class="brand-title mb-2">
					Les clés du Capcir
				</div>
				<h1 class="display-5 fw-bold mb-4">
					Vente & Conciergerie<br>
					Au cœur du Capcir
				</h1>
				<div class="d-flex flex-wrap gap-3 align-items-center pt-3">
					<a href="#" data-bs-toggle="modal" data-bs-target="#modal_booking" class="btn btn-sapin">Réserver maintenant</a>
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
						<div class="text-muted small">Service complet</div>
					</div>
					</div>
				</div>
			</div>

			<div class="col-6 col-lg-3">
				<div class="service-card">
					<div class="service-box">
					<div class="service-icon" aria-hidden="true">
						<i class="fa-solid fa-key"></i>
					</div>
					<div>
						<div class="fw-bold fs-5">Remise de clé</div>
						<div class="text-muted small">24h/24 - 7j/7</div>
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
						<div class="fw-bold fs-5">Ménage & Linge</div>
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

					<div class="row g-3">
						<?php if (empty($ventes)): ?>
							<div class="col-12">
								<div class="alert alert-info mb-0">
									Aucun bien en vente pour le moment.
								</div>
							</div>
						<?php else: ?>
							<?php foreach ($ventes as $bien): ?>
								<div class="col-12">
									<div class="listing">
										<div class="listing-image-container">
											<?php if (!empty($bien['images'])): ?>
												<img src="<?= htmlspecialchars($bien['images'][0]['url']) ?>" alt="<?= htmlspecialchars($bien['titre']) ?>">
											<?php else: ?>
												<div class="listing-image-placeholder bg-secondary d-flex align-items-center justify-content-center text-white">
													<i class="fa-solid fa-image fa-3x opacity-50"></i>
												</div>
											<?php endif; ?>
											<div class="listing-image-overlay">
												<h5 class="listing-image-title"><?= htmlspecialchars($bien['titre']) ?></h5>
											</div>
										</div>
										<div class="body">
											<div class="mb-2 fw-bold small">
												<?php if (!empty($bien['surface'])): ?>
													<?= htmlspecialchars($bien['surface']) ?> m²
												<?php endif; ?>
												<?php if (!empty($bien['nb_chambres'])): ?>
													<?= !empty($bien['surface']) ? ' • ' : '' ?><?= htmlspecialchars($bien['nb_chambres']) ?> ch.
												<?php endif; ?>
												<?php if (empty($bien['surface']) && empty($bien['nb_chambres'])): ?>
													&nbsp;
												<?php endif; ?>
											</div>
											<div class="text-muted small mb-3">
												<?php if (!empty($bien['lieu'])): ?>
													<i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($bien['lieu']) ?>
												<?php endif; ?>
											</div>
											<?php if (!empty($bien['prix'])): ?>
												<div class="prix prix-right text-nowrap">
													<?= number_format($bien['prix'], 0, ',', ' ') ?> €
												</div>
											<?php endif; ?>
											<?php if (!empty($bien['description'])): ?>
												<p class="small text-muted mb-3">
													<?= htmlspecialchars(mb_substr($bien['description'], 0, 80)) ?><?= mb_strlen($bien['description']) > 80 ? '...' : '' ?>
												</p>
											<?php endif; ?>
											<div class="d-flex gap-2">
												<button type="button" class="btn btn-outline-sapin btn-sm" onclick="showDetailModal(<?= $bien['id'] ?>)">Détails</button>
												<a class="btn btn-sapin btn-sm" href="#contact">Infos / visite</a>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
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

					<div class="row g-3">
						<?php if (empty($locations)): ?>
							<div class="col-12">
								<div class="alert alert-info mb-0">
									Aucun bien en location pour le moment.
								</div>
							</div>
						<?php else: ?>
							<?php foreach ($locations as $bien): ?>
								<div class="col-12">
									<div class="listing">
										<div class="listing-image-container">
											<?php if (!empty($bien['images'])): ?>
												<img src="<?= htmlspecialchars($bien['images'][0]['url']) ?>" alt="<?= htmlspecialchars($bien['titre']) ?>">
											<?php else: ?>
												<div class="listing-image-placeholder bg-secondary d-flex align-items-center justify-content-center text-white">
													<i class="fa-solid fa-image fa-3x opacity-50"></i>
												</div>
											<?php endif; ?>
											<div class="listing-image-overlay">
												<h5 class="listing-image-title"><?= htmlspecialchars($bien['titre']) ?></h5>
											</div>
										</div>
										<div class="body">
											<div class="mb-2 fw-bold small">
												<?php if (!empty($bien['nb_chambres'])): ?>
													<?= htmlspecialchars($bien['nb_chambres']) ?> ch.
												<?php endif; ?>
												<?php if (!empty($bien['nb_personnes'])): ?>
													<?= !empty($bien['nb_chambres']) ? ' • ' : '' ?><?= htmlspecialchars($bien['nb_personnes']) ?> pers.
												<?php endif; ?>
											</div>
											<div class="text-muted small mb-3">
												<?php if (!empty($bien['lieu'])): ?>
													<i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($bien['lieu']) ?>
												<?php endif; ?>
											</div>

											<?php if (!empty($bien['description'])): ?>
												<p class="small text-muted mb-3">
													<?= htmlspecialchars(mb_substr($bien['description'], 0, 80)) ?><?= mb_strlen($bien['description']) > 80 ? '...' : '' ?>
												</p>
											<?php endif; ?>
											<div class="d-flex gap-2">
												<button type="button" class="btn btn-outline-sapin btn-sm" onclick="showDetailModal(<?= $bien['id'] ?>)">Détails</button>
												<button type="button" class="btn btn-sapin btn-sm" onclick="openBookingModal(<?= !empty($bien['id_smoobu']) ? htmlspecialchars($bien['id_smoobu']) : null ?>, '<?= !empty($bien['id_smoobu']) ? htmlspecialchars($bien['titre']) : null ?>')">Réserver</button>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
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