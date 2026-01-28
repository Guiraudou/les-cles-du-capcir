<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Capcir — Vente & Conciergerie</title>

	<!-- Bootstrap 5 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<link rel="stylesheet" href="style.css">

	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,600;1,400&display=swap" rel="stylesheet">

	<!-- Font Awesome 6 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<!-- Sticky header (appears on scroll) -->
<nav id="stickyNav" class="navbar navbar-expand-lg sticky-nav">
	<div class="container py-1">
		<a class="navbar-brand" href="#top">
			<img src="logo.png" alt="Les clés du Capcir">
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
					<a class="btn btn-sapin" href="reserver.html">Réserver</a>
				</li>
			</ul>
		</div>
	</div>
</nav>

<!-- HERO (with integrated menu) -->
<header id="top" class="hero" style="--hero-image: url('');">

	<!-- Logo en haut à gauche -->
	<div class="hero-logo-corner">
		<img src="logo.png" alt="Les clés du Capcir">
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
				<a class="btn-ghost" href="reserver.html">Réserver</a>
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
						<a class="btn-ghost" href="reserver.html">Réserver</a>
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
					<a href="reserver.html" class="btn btn-sapin">Réserver maintenant</a>
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
						<div class="col-12">
							<div class="listing">
								<img src="https://images.unsplash.com/photo-1449844908441-8829872d2607?auto=format&fit=crop&w=1600&q=80" alt="Vente 1">
								<div class="body">
									<div class="fw-bold">Chalet • 120 m² • 4 ch</div>
									<div class="text-muted small mb-3">Les Angles • Terrasse • Vue montagne</div>
									<div class="d-flex gap-2">
										<a class="btn btn-outline-sapin btn-sm btn-details" href="vente.html">Détails</a>
										<a class="btn btn-sapin btn-sm" href="#contact">Infos / visite</a>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12">
							<div class="listing">
								<img src="https://images.unsplash.com/photo-1501183638710-841dd1904471?auto=format&fit=crop&w=1600&q=80" alt="Vente 2">
								<div class="body">
									<div class="fw-bold">Appartement • 65 m² • 2 ch</div>
									<div class="text-muted small mb-3">Font-Romeu • Balcon • Proche centre</div>
									<div class="d-flex gap-2">
										<a class="btn btn-outline-sapin btn-sm btn-details" href="vente.html">Détails</a>
										<a class="btn btn-sapin btn-sm" href="#contact">Infos / visite</a>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12">
							<div class="listing">
								<img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1600&q=80" alt="Vente 3">
								<div class="body">
									<div class="fw-bold">Studio • 28 m²</div>
									<div class="text-muted small mb-3">Matemale • Investissement • Rendement locatif</div>
									<div class="d-flex gap-2">
										<a class="btn btn-outline-sapin btn-sm btn-details" href="vente.html">Détails</a>
										<a class="btn btn-sapin btn-sm" href="#contact">Infos / visite</a>
									</div>
								</div>
							</div>
						</div>

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
						<div class="col-12">
							<div class="listing">
								<img src="https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1600&q=80" alt="Location 1">
								<div class="body">
									<div class="fw-bold">Chalet cosy • 6 pers</div>
									<div class="text-muted small mb-3">Les Angles • 3 chambres • Parking</div>
									<div class="d-flex gap-2">
										<a class="btn btn-outline-sapin btn-sm" href="locations.html">Détails</a>
										<a class="btn btn-sapin btn-sm" href="reserver.html">Réserver</a>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12">
							<div class="listing">
								<img src="https://images.unsplash.com/photo-1482192505345-5655af888cc4?auto=format&fit=crop&w=1600&q=80" alt="Location 2">
								<div class="body">
									<div class="fw-bold">Appartement • 4 pers</div>
									<div class="text-muted small mb-3">Font-Romeu • 2 chambres • Balcon</div>
									<div class="d-flex gap-2">
										<a class="btn btn-outline-sapin btn-sm" href="locations.html">Détails</a>
										<a class="btn btn-sapin btn-sm" href="reserver.html">Réserver</a>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12">
							<div class="listing">
								<img src="https://images.unsplash.com/photo-1516455207990-7a41ce80f7ee?auto=format&fit=crop&w=1600&q=80" alt="Location 3">
								<div class="body">
									<div class="fw-bold">Studio • 2 pers</div>
									<div class="text-muted small mb-3">Matemale • Wifi • Proche nature</div>
									<div class="d-flex gap-2">
										<a class="btn btn-outline-sapin btn-sm" href="locations.html">Détails</a>
										<a class="btn btn-sapin btn-sm" href="reserver.html">Réserver</a>
									</div>
								</div>
							</div>
						</div>

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
				<form onsubmit="return false;">
					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label">Nom</label>
							<input class="form-control" type="text" placeholder="Votre nom">
						</div>
						<div class="col-md-6">
							<label class="form-label">Email</label>
							<input class="form-control" type="email" placeholder="vous@email.fr">
						</div>
						<div class="col-md-6">
							<label class="form-label">Téléphone (optionnel)</label>
							<input class="form-control" type="tel" placeholder="+33 ...">
						</div>
						<div class="col-md-6">
							<label class="form-label">Sujet</label>
							<select class="form-select">
								<option>Vente</option>
								<option>Location</option>
								<option>Conciergerie</option>
							</select>
						</div>
						<div class="col-12">
							<label class="form-label">Message</label>
							<textarea class="form-control" rows="4" placeholder="Décrivez votre demande..."></textarea>
						</div>
						<div class="col-12 text-center">
							<button class="btn btn-sapin" type="submit" onclick="fakeSend()">Envoyer</button>
							<div id="formStatus" class="text-muted small mt-2"></div>
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
				<img src="logo_white.png" alt="Les clés du Capcir" class="footer-logo">
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
					</div>
				</div>
			</div>

		</div>
	</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
	// Image demandée: ciel bleu + chalet + montagnes enneigées
	// Remplace par ta propre image si tu en as une. Celle-ci correspond à l'esprit demandé.
	const HERO_IMAGE =
		"home.webp";
		//"https://images.unsplash.com/photo-1578309830739-6226cb317b22?auto=format&fit=crop&q=80&w=1740";

	function setHeroImage() {
		const hero = document.querySelector(".hero");
		hero.style.setProperty("--hero-image", `url('${HERO_IMAGE}')`);
	}

	function fakeSend(){
		const el = document.getElementById("formStatus");
		el.textContent = "Message prêt à envoyer (à brancher sur un backend / email).";
		setTimeout(()=> el.textContent = "", 3500);
	}

	// Sticky menu appears after scrolling past hero a bit
	function handleStickyNav(){
		const sticky = document.getElementById("stickyNav");
		const y = window.scrollY || document.documentElement.scrollTop;
		if (y > 120){
			sticky.classList.add("show");
			document.body.classList.add("is-sticky");
		} else {
			sticky.classList.remove("show");
			document.body.classList.remove("is-sticky");
		}
	}

	document.getElementById("year").textContent = new Date().getFullYear();
	setHeroImage();
	handleStickyNav();
	window.addEventListener("scroll", handleStickyNav);
</script>
</body>
</html>