<footer class="mt-4">
	<div class="container">
		<div class="row g-4 mb-4">

			<!-- Colonne 1 : Contact avec icônes -->
			<div class="col-6 col-md-4 text-center order-2">
				<div class="footer-contact-item">
					<i class="fa-solid fa-envelope"></i><br>
					<a href="mailto:<?= EMAIL_DESTINATAIRE ?>" class="small text-decoration-none"><?= EMAIL_DESTINATAIRE ?></a>
				</div>
				<div class="footer-contact-item">
					<i class="fa-solid fa-phone"></i><br>
					<a href="tel:<?= PHONE ?>" class="small text-decoration-none"><?= \Osimatic\Messaging\PhoneNumber::formatInternational(PHONE) ?></a>
				</div>
			</div>

			<!-- Colonne 2 : Logo et titre (centre) -->
			<div class="col-12 col-md-4 text-center order-1 order-md-2">
				<img src="images/logo_white.png" alt="Les Clés du Capcir" class="footer-logo">
				<h5 class="fw-bold mb-2">Les Clés du Capcir</h5>
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
						<a href="index.php" class="link-underlined text-muted small">Accueil</a>
						<a href="location.php" class="link-underlined text-muted small">Locations</a>
						<a href="vente.php" class="link-underlined text-muted small">Ventes</a>
						<a href="index.php#contact" class="link-underlined text-muted small">Contact</a>
					</div>
				</div>
				<div class="col-12 col-md-6 text-end">
					<div class="d-flex gap-3 justify-content-end flex-wrap small mb-1">
						<a href="cgv.php" class="link-underlined text-muted">CGV</a>
						<a href="mentions-legales.php" class="link-underlined text-muted">Mentions légales</a>
						<a href="confidentialite.php" class="link-underlined text-muted">Confidentialité</a>
						<a href="#" class="link-underlined text-muted" data-bs-toggle="modal" data-bs-target="#modalLogin">Administration</a>
					</div>
					<div class="text-muted small">© <span id="year"></span> Les Clés du Capcir — Tous droits réservés</div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
	// Image demandée: ciel bleu + chalet + montagnes enneigées
	// Remplace par ta propre image si tu en as une. Celle-ci correspond à l'esprit demandé.
	const HERO_IMAGE = "images/home.webp";
	//"https://images.unsplash.com/photo-1578309830739-6226cb317b22?auto=format&fit=crop&q=80&w=1740";

	// Chemin des uploads (depuis config.php)
	const UPLOADS_PATH = "<?= UPLOADS_PATH ?>";

	const MAX_IMAGES_UPLOAD = <?= MAX_IMAGES_UPLOAD ?>;

	const IS_ADMIN_LOGGED_IN = <?= isset($_SESSION['admin_id']) ? 'true' : 'false' ?>;

	// Année dynamique
	document.getElementById('year').textContent = new Date().getFullYear();

	document.getElementById('modalLogin').addEventListener('show.bs.modal', function(e) {
		if (IS_ADMIN_LOGGED_IN) {
			e.preventDefault();
			window.location.href = 'admin.php';
		}
	});
</script>

<script src="app.js?<?= ASSET_TOKEN; ?>"></script>

</body>
</html>