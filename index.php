<?php
require_once 'includes/config.php';

// Charger les biens depuis la base de données
$bienModel = new Bien();
$allBiens = $bienModel->getAll();

// Filtrer et limiter les biens actifs
$ventes = array_slice(array_filter($allBiens, fn ($b) => $b['statut'] === 'vente' && $b['actif']), 0, 3);
$locations = array_slice(array_filter($allBiens, fn ($b) => $b['statut'] === 'location' && $b['actif']), 0, 3);

?>
<?php require_once 'header.inc.php'; ?>

<!-- Sticky header (appears on scroll) -->
<nav id="stickyNav" class="navbar navbar-expand-lg sticky-nav">
	<div class="container py-1">
		<a class="navbar-brand" href="#top">
			<img src="data/logo.png" alt="Les clés du Capcir">
			Les clés du Capcir
		</a>

		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#stickyMenu">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="stickyMenu">
			<ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
				<li class="nav-item"><a class="nav-link" href="#top">Accueil</a></li>
				<li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
				<li class="nav-item"><a class="nav-link" href="#biens">Biens</a></li>
				<li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
				<li class="nav-item ms-lg-2">
					<a class="btn btn-sapin" href="#" data-bs-toggle="modal" data-bs-target="#modal_booking">Réserver</a>
				</li>
			</ul>
		</div>
	</div>
</nav>

<!-- HERO (with integrated menu) -->
<header id="top" class="hero">

	<!-- Logo en haut à gauche -->
	<div class="hero-logo-corner">
		<img src="data/logo.png" alt="Les clés du Capcir">
	</div>

	<!-- integrated menu in hero -->
	<div class="hero-nav">
		<div class="container d-flex align-items-center justify-content-between">
			<div class="brand"></div>

			<div class="d-none d-lg-flex align-items-center gap-4">
				<a class="nav-link" href="#top">Accueil</a>
				<a class="nav-link" href="#services">Services</a>
				<a class="nav-link" href="#biens">Biens</a>
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
					<a href="vente.html" class="btn btn-outline-sapin">Biens en vente</a>
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
						<a href="vente.html" class="text-decoration-none link-voir-tout">Tout voir →</a>
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
										<?php
										$firstImage = !empty($bien['images']) ? $bien['images'][0] : 'https://images.unsplash.com/photo-1449844908441-8829872d2607?auto=format&fit=crop&w=1600&q=80';
										?>
										<img src="<?= htmlspecialchars($firstImage) ?>" alt="<?= htmlspecialchars($bien['titre']) ?>">
										<div class="body">
											<div class="fw-bold">
												<?= htmlspecialchars($bien['titre']) ?>
												<?php if (!empty($bien['surface'])): ?>
													• <?= htmlspecialchars($bien['surface']) ?> m²
												<?php endif; ?>
												<?php if (!empty($bien['nb_chambres'])): ?>
													• <?= htmlspecialchars($bien['nb_chambres']) ?> ch
												<?php endif; ?>
											</div>
											<div class="text-muted small mb-3">
												<?php if (!empty($bien['lieu'])): ?>
													<?= htmlspecialchars($bien['lieu']) ?>
												<?php endif; ?>
												<?php if (!empty($bien['description'])): ?>
													• <?= htmlspecialchars(mb_substr($bien['description'], 0, 50)) ?><?= mb_strlen($bien['description']) > 50 ? '...' : '' ?>
												<?php endif; ?>
											</div>
											<?php if (!empty($bien['prix'])): ?>
												<div class="fw-bold text-success mb-2"><?= number_format($bien['prix'], 0, ',', ' ') ?> €</div>
											<?php endif; ?>
											<div class="d-flex gap-2">
												<a class="btn btn-outline-sapin btn-sm btn-details" href="vente.html">Détails</a>
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
						<a href="locations.html" class="text-decoration-none link-voir-tout">Tout voir →</a>
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
										<?php
										$firstImage = !empty($bien['images']) ? $bien['images'][0] : 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1600&q=80';
										?>
										<img src="<?= htmlspecialchars($firstImage) ?>" alt="<?= htmlspecialchars($bien['titre']) ?>">
										<div class="body">
											<div class="fw-bold">
												<?= htmlspecialchars($bien['titre']) ?>
												<?php if (!empty($bien['nb_personnes'])): ?>
													• <?= htmlspecialchars($bien['nb_personnes']) ?> pers
												<?php endif; ?>
											</div>
											<div class="text-muted small mb-3">
												<?php if (!empty($bien['lieu'])): ?>
													<?= htmlspecialchars($bien['lieu']) ?>
												<?php endif; ?>
												<?php if (!empty($bien['nb_chambres'])): ?>
													• <?= htmlspecialchars($bien['nb_chambres']) ?> chambres
												<?php endif; ?>
												<?php if (!empty($bien['description'])): ?>
													• <?= htmlspecialchars(mb_substr($bien['description'], 0, 40)) ?><?= mb_strlen($bien['description']) > 40 ? '...' : '' ?>
												<?php endif; ?>
											</div>
											<div class="d-flex gap-2">
												<a class="btn btn-outline-sapin btn-sm" href="locations.html">Détails</a>
												<a class="btn btn-sapin btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#modal_booking">Réserver</a>
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

		<div class="listing contact-form-container">
			<div class="body p-4">
				<form id="contactForm" method="post">
					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label" for="nom">Nom <span class="text-danger">*</span></label>
							<input class="form-control" type="text" id="nom" name="nom" placeholder="Votre nom" required>
						</div>
						<div class="col-md-6">
							<label class="form-label" for="email">Email <span class="text-danger">*</span></label>
							<input class="form-control" type="email" id="email" name="email" placeholder="vous@email.fr" required>
						</div>
						<div class="col-md-6">
							<label class="form-label" for="telephone">Téléphone (optionnel)</label>
							<input class="form-control" type="tel" id="telephone" name="telephone" placeholder="+33 ...">
						</div>
						<div class="col-md-6">
							<label class="form-label" for="sujet">Sujet <span class="text-danger">*</span></label>
							<select class="form-select" id="sujet" name="sujet" required>
								<option value="">Choisissez un sujet</option>
								<option value="Vente">Vente</option>
								<option value="Location">Location</option>
								<option value="Conciergerie">Conciergerie</option>
								<option value="Autre">Autre</option>
							</select>
						</div>
						<div class="col-12">
							<label class="form-label" for="message">Message <span class="text-danger">*</span></label>
							<textarea class="form-control" id="message" name="message" rows="4" placeholder="Décrivez votre demande..." required></textarea>
						</div>
						<!-- Honeypot anti-spam (caché) -->
						<input type="text" name="honeypot" style="display:none;" tabindex="-1" autocomplete="off">
						<div class="col-12 text-center">
							<button class="btn btn-sapin" type="submit" id="submitBtn">
								<span id="btnText">Envoyer</span>
								<span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
							</button>
							<div id="formStatus" class="mt-2"></div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<!-- FOOTER -->
<footer>
	<div class="container">
		<div class="row g-4 mb-4">

			<!-- Colonne 1 : Contact avec icônes -->
			<div class="col-6 col-md-4 text-center order-2">
				<!--<div class="footer-contact-item">
					<i class="fa-solid fa-location-dot"></i>
					<span class="small">Capcir & alentours</span>
				</div>-->
				<div class="footer-contact-item">
					<i class="fa-solid fa-envelope"></i><br>
					<span class="small">contact@exemple.fr</span>
				</div>
				<div class="footer-contact-item">
					<i class="fa-solid fa-phone"></i><br>
					<span class="small">+33 6 00 00 00 00</span>
				</div>
			</div>

			<!-- Colonne 2 : Logo et titre (centre) -->
			<div class="col-12 col-md-4 text-center order-1 order-md-2">
				<img src="data/logo_white.png" alt="Les clés du Capcir" class="footer-logo">
				<h5 class="fw-bold mb-2">Les clés du Capcir</h5>
				<p class="text-muted small mb-3">Vente & Conciergerie au cœur du Capcir</p>
				
			</div>

			<!-- Colonne 3 : Liens -->
			<div class="col-6 col-md-4 order-3 order-md-3 text-end">
				<a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer" class="footer-social-icon">
					<i class="fa-brands fa-instagram"></i>
				</a>
			</div>
		</div>



		<div class="border-top pt-3">
			<div class="row">
				<div class="col-12 col-md-6">
					<div class="">
						<a href="#top" class="link-underlined text-muted small">Accueil</a>
						<a href="#services" class="link-underlined text-muted small">Services</a>
						<a href="#biens" class="link-underlined text-muted small">Biens</a>
						<a href="#contact" class="link-underlined text-muted small">Contact</a>
					</div>
				</div>
				<div class="col-12 col-md-6 text-end">
					<div class="d-flex gap-3 justify-content-end flex-wrap small">
						<div class="text-muted">© <span id="year"></span> Tous droits réservés</div>
						<a href="#" class="link-underlined text-muted">Mentions légales</a>
						<a href="#" class="link-underlined text-muted">Confidentialité</a>
						<a href="#" class="link-underlined text-muted" data-bs-toggle="modal" data-bs-target="#modalLogin">Administration</a>
					</div>
				</div>
			</div>

		</div>
	</div>
</footer>

<!-- Modal Connexion Admin -->
<div class="modal fade" id="modalLogin" tabindex="-1" aria-labelledby="modalLoginLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLoginLabel">
					<i class="fa-solid fa-user-lock"></i> Administration
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
			</div>
			<div class="modal-body">
				<div id="login-alert"></div>
				<form id="loginForm">
					<div class="mb-3">
						<label for="login-username" class="form-label">Nom d'utilisateur</label>
						<input type="text" class="form-control" id="login-username" name="username" required autofocus>
					</div>
					<div class="mb-3">
						<label for="login-password" class="form-label">Mot de passe</label>
						<input type="password" class="form-control" id="login-password" name="password" required>
					</div>
					<button type="submit" class="btn btn-success w-100">
						<i class="fa-solid fa-right-to-bracket"></i> Se connecter
					</button>
				</form>
			</div>
		</div>
	</div>
</div>

<?php include 'booking.inc.php'; ?>

<?php require_once 'footer.inc.php'; ?>