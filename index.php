<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Capcir — Vente & Conciergerie</title>

	<!-- Bootstrap 5 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,600;1,400&display=swap" rel="stylesheet">

	<!-- Font Awesome 6 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

	<style>
		/* =================================
		   VARIABLES ET BASE
		   ================================= */
		:root{
			--sapin: #0f3d2e;
			--sapin-2: #14543f;
			--text: #1f2937;
			--muted: #6b7280;
			--border: #e5e7eb;
			--bg: #fafbfb;
			--shadow: 0 8px 24px rgba(17,24,39,.06);
			--shadow-lg: 0 20px 40px rgba(17,24,39,.12);
		}

		html, body{
			background: var(--bg);
			color: var(--text);
			font-size: 18px;
		}

		.text-muted{
			color: var(--muted) !important;
		}

		[id]{
			scroll-margin-top: 90px;
		}

		/* =================================
		   BOUTONS
		   ================================= */
		.btn-sapin{
			background: var(--sapin);
			border-color: var(--sapin);
			color: #fff;
			border-radius: 999px;
			padding: .85rem 1.5rem;
			font-weight: 650;
			font-size: 1.1rem;
			box-shadow: 0 4px 12px rgba(15,61,46,.2);
		}

		.btn-sapin:hover{
			background: var(--sapin-2);
			border-color: var(--sapin-2);
			color: #fff;
		}

		.btn-outline-sapin{
			border-color: var(--sapin);
			color: var(--sapin);
			border-radius: 999px;
			padding: .85rem 1.5rem;
			font-weight: 650;
			font-size: 1.1rem;
			border-width: 2px;
		}

		.btn-outline-sapin:hover{
			background: var(--sapin);
			color: #fff;
		}

		.btn-contact-arrow{
			color: var(--sapin);
			font-weight: 800;
			padding: .85rem 0;
		}

		/* =================================
		   NAVIGATION
		   ================================= */
		/* Navigation sticky (apparaît au scroll) */
		.sticky-nav{
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			z-index: 999;
			background: rgba(255,255,255,.94);
			backdrop-filter: blur(10px);
			border-bottom: 1px solid var(--border);
			transform: translateY(-110%);
			transition: transform .25s ease;
		}

		.sticky-nav.show{
			transform: translateY(0);
		}

		.sticky-nav .navbar-brand{
			color: var(--sapin) !important;
			font-weight: 800;
			letter-spacing: .2px;
			font-size: 1.15rem;
			display: flex;
			align-items: center;
			gap: .6rem;
		}

		.sticky-nav .navbar-brand img{
			height: 48px;
			width: 48px;
			border-radius: 10px;
			object-fit: cover;
		}

		.sticky-nav .navbar-brand .logoMark{
			width: 28px;
			height: 28px;
			border-radius: 10px;
			background: rgba(15,61,46,.12);
			border: 1px solid rgba(15,61,46,.18);
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: 900;
			color: var(--sapin);
		}

		.sticky-nav .nav-link{
			color: var(--text) !important;
			font-weight: 650;
			font-size: 1.05rem;
		}

		.sticky-nav .nav-link:hover{
			color: var(--sapin) !important;
		}

		/* =================================
		   HERO / BANNIERE
		   ================================= */
		.hero{
			position: relative;
			min-height: 52vh;
			display: flex;
			align-items: center;
			overflow: hidden;
			border-bottom: 1px solid var(--border);
		}

		.hero::before{
			content: "";
			position: absolute;
			inset: 0;
			background:
				linear-gradient(90deg, rgba(255,255,255,.85) 0%, rgba(255,255,255,.65) 44%, rgba(255,255,255,.25) 72%, rgba(255,255,255,.1) 100%),
				var(--hero-image) center center/cover no-repeat;
			transform: scale(1.02);
		}

		.hero > .container{
			position: relative;
			z-index: 1;
		}

		body:not(.is-sticky) .hero h1{
			font-size: clamp(3rem, 4.5vw, 4rem);
			line-height: 1.1;
		}

		/* Logo hero en position absolute */
		.hero-logo-corner{
			position: absolute;
			top: 40px;
			left: 40px;
			z-index: 3;
		}

		.hero-logo-corner img{
			width: 16vh;
		}

		/* Titre marque "Les clés du Capcir" */
		.brand-title{
			font-family: 'Montserrat', sans-serif;
			font-size: 2.75rem;
			font-weight: bold;
			color: #0f3d2e;
			text-shadow: 0 2px 8px rgba(0,0,0,.2);
			/*font-style: italic;*/
			letter-spacing: 0.5px;
		}

		/* Hero content avec padding left */
		.hero-content{
			padding-left: 6rem;
		}

		/* Navigation intégrée dans le hero */
		.hero-nav{
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			z-index: 2;
			padding: 18px 0;
			background: linear-gradient(180deg, rgba(15,61,46,.6) 0%, transparent 100%);
		}

		.hero-nav .nav-link{
			color: #e8f4f1;
			font-weight: 700;
			font-size: 1.25rem;
			text-shadow: 0 1px 2px rgba(0,0,0,.3);
		}

		.hero-nav .nav-link:hover{
			color: #fff;
			text-decoration: underline;
			text-underline-offset: 6px;
		}

		.hero-nav .brand{
			color: #fff;
			font-weight: 800;
			letter-spacing: .2px;
			text-decoration: none;
			display: flex;
			align-items: center;
			gap: .8rem;
			text-shadow: 0 2px 8px rgba(0,0,0,.4);
		}

		.hero-nav .brand img{
			width: 50px;
			height: 50px;
			border-radius: 12px;
			object-fit: cover;
			box-shadow: 0 2px 8px rgba(0,0,0,.3);
		}

		.hero-nav .brand .logoMark{
			width: 28px;
			height: 28px;
			border-radius: 10px;
			background: rgba(255,255,255,.18);
			border: 1px solid rgba(255,255,255,.28);
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: 900;
		}

		.hero-nav .btn-ghost{
			border: 2px solid #e8f4f1;
			color: #e8f4f1;
			border-radius: 999px;
			padding: .75rem 1.4rem;
			font-size: 1.25rem;
			font-weight: 700;
			background: rgba(255,255,255,.15);
			backdrop-filter: blur(12px);
			text-shadow: 0 1px 2px rgba(0,0,0,.3);
			text-decoration: none !important;
		}

		.hero-nav .btn-ghost:hover{
			background: rgba(255,255,255,.25);
			border-color: #e8f4f1;
			color: #e8f4f1;
			text-decoration: none !important;
		}

		/* =================================
		   SERVICES
		   ================================= */
		.service-card{
			background: #fff;
			border-radius: 18px;
			padding: 36px 20px;
			text-align: center;
			box-shadow: var(--shadow);
			border: 1px solid var(--border);
			transition: all 0.3s ease;
			height: 100%;
		}

		.service-card:hover{
			transform: scale(1.05);
		}

		.service-box{
			display: flex;
			flex-direction: column;
			gap: 18px;
			align-items: center;
			justify-content: center;
			font-size: 1.05rem;
		}

		.service-box .service-icon{
			width: 90px;
			height: 90px;
			border-radius: 50%;
			background: linear-gradient(135deg, var(--sapin) 0%, var(--sapin-2) 100%);
			display: flex;
			align-items: center;
			justify-content: center;
			color: #fff;
			box-shadow: 0 8px 24px rgba(15,61,46,.25);
		}

		.service-box .service-icon i{
			font-size: 40px;
		}

		/* =================================
		   SECTIONS ET TITRES
		   ================================= */
		section{
			margin: 80px 0;
		}

		.section-title{
			font-weight: 850;
			letter-spacing: .2px;
			font-size: 2rem;
			margin-bottom: 0;
			color: var(--sapin);
		}

		/* =================================
		   LISTINGS / CARTES BIENS
		   ================================= */
		.listing{
			border: 1px solid var(--border);
			border-radius: 18px;
			overflow: hidden;
			background: #fff;
			box-shadow: var(--shadow);
			height: 100%;
		}

		.listing img{
			width: 100%;
			height: 220px;
			object-fit: cover;
			transition: transform 0.3s ease;
		}

		.listing:hover img{
			transform: scale(1.1);
		}

		.listing .body{
			padding: 22px;
			font-size: 1.05rem;
		}

		.listing .btn-sm{
			padding: .5rem 1rem;
			font-size: 0.95rem;
		}

		/* Colonnes Ventes & Locations */
		.biens-row{
			--bs-gutter-x: 2rem;
		}

		.vente-col{
			background: linear-gradient(135deg, var(--sapin) 0%, #1a6849 100%);
			border-radius: 24px;
			padding: 40px 36px;
			box-shadow: 0 12px 32px rgba(15,61,46,.25);
		}

		.vente-col .section-title{
			color: #fff !important;
		}

		.vente-col .listing{
			border: 1px solid rgba(255,255,255,.15);
			background: rgba(255,255,255,.95);
		}

		.vente-col .btn-outline-sapin{
			border-color: var(--sapin) !important;
			color: var(--sapin) !important;
			background: #fff !important;
		}

		.vente-col .btn-outline-sapin:hover{
			background: var(--sapin) !important;
			color: #fff !important;
		}

		.vente-col .btn-details{
			color: var(--sapin) !important;
			background: #fff !important;
		}

		.vente-col .link-voir-tout{
			color: #fff;
			font-weight: 800;
		}

		.location-col{
			background: transparent;
			border: 2px solid var(--border);
			border-radius: 24px;
			padding: 40px 36px;
		}

		.location-col .link-voir-tout{
			color: var(--sapin);
			font-weight: 800;
		}

		/* =================================
		   FORMULAIRES
		   ================================= */
		.form-control,
		.form-select{
			border-radius: 12px;
			padding: .75rem 1rem;
			font-size: 1.05rem;
			border: 2px solid var(--border);
			transition: all 0.3s ease;
		}

		.form-control:focus,
		.form-select:focus{
			border-color: var(--sapin);
			box-shadow: 0 0 0 3px rgba(15,61,46,.1);
		}

		.contact-form-container{
			box-shadow: var(--shadow);
			max-width: 900px;
			margin: 0 auto;
		}

		/* =================================
		   FOOTER
		   ================================= */
		footer{
			background: linear-gradient(135deg, #0f3d2e 0%, #14543f 100%);
			padding: 60px 0 15px 0;
			color: #fff;
		}

		footer h5,
		footer h6{
			color: #fff !important;
			font-weight: 800;
			margin-bottom: 1.5rem;
		}

		footer .text-muted{
			color: rgba(255,255,255,.8) !important;
		}

		footer a{
			color: rgba(255,255,255,.9);
			transition: color 0.3s ease;
		}

		footer a:hover{
			color: #fff;
		}

		footer .footer-social-icon{
			display: inline-block;
			color: rgba(255,255,255,.8);
			transition: all 0.3s ease;
		}

		footer .footer-social-icon:hover{
			color: #fff;
			transform: scale(1.1);
		}

		footer .footer-social-icon i{
			font-size: 32px;
		}

		footer .footer-contact-item{
			/*display: flex;*/
			align-items: center;
			gap: 10px;
			margin-bottom: 12px;
			color: rgba(255,255,255,.9);
		}

		footer .footer-contact-item i{
			font-size: 18px;
			width: 24px;
		}

		footer .border-top{
			border-top: 1px solid rgba(255,255,255,.2) !important;
			padding-top: 24px;
			margin-top: 30px;
		}

		.footer-logo{
			height: 70px;
			margin-bottom: 5px;
		}

		.footer-heading-alt{
			color: var(--sapin);
		}
	</style>
</head>

<body>

<!-- Sticky header (appears on scroll) -->
<nav id="stickyNav" class="navbar navbar-expand-lg sticky-nav">
	<div class="container py-1">
		<a class="navbar-brand" href="#top">
			<img src="logo_with_text.png" alt="Les clés du Capcir">
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

			<!-- mobile toggle -> links to sticky menu via scroll -->
			<div class="d-lg-none">
				<a class="btn-ghost" href="#services">Menu</a>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-xl-9 hero-content">
				<div class="brand-title mb-1">
					Les clés du Capcir
				</div>
				<h1 class="display-5 fw-bold mb-4">
					Vente & Conciergerie<br>
					Au cœur du Capcir
				</h1>
				<div class="d-flex flex-wrap gap-3 align-items-center">
					<a href="reserver.html" class="btn btn-sapin">Réserver maintenant</a>
					<a href="vente.html" class="btn btn-outline-sapin">Biens en vente</a>
					<a href="#contact" class="btn btn-link text-decoration-none btn-contact-arrow">
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
		<div class="row">
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
			<!-- Colonne 1 : Liens -->
			<div class="col-md-4">
				<div class="d-flex flex-column gap-2 text-center">
					<a href="#top" class="text-decoration-none text-muted small">Accueil</a>
					<a href="#services" class="text-decoration-none text-muted small">Services</a>
					<a href="#biens" class="text-decoration-none text-muted small">Biens</a>
					<a href="#contact" class="text-decoration-none text-muted small">Contact</a>
				</div>
			</div>

			<!-- Colonne 2 : Logo et titre (centre) -->
			<div class="col-md-4 text-center">
				<img src="logo_white.png" alt="Les clés du Capcir" class="footer-logo">
				<h5 class="fw-bold mb-2">Les clés du Capcir</h5>
				<p class="text-muted small mb-3">Vente & Conciergerie au cœur du Capcir</p>
				<a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer" class="footer-social-icon">
					<i class="fa-brands fa-instagram"></i>
				</a>
			</div>

			<!-- Colonne 3 : Contact avec icônes -->
			<div class="col-md-4 text-center">
				<div class="footer-contact-item">
					<i class="fa-solid fa-location-dot"></i>
					<span class="small">Capcir & alentours</span>
				</div>
				<div class="footer-contact-item">
					<i class="fa-solid fa-envelope"></i>
					<span class="small">contact@exemple.fr</span>
				</div>
				<div class="footer-contact-item">
					<i class="fa-solid fa-phone"></i>
					<span class="small">+33 6 00 00 00 00</span>
				</div>
			</div>
		</div>
		<div class="border-top pt-3">
			<div class="d-flex gap-3 justify-content-center flex-wrap small">
				<p class="text-muted">© <span id="year"></span> Tous droits réservés</p>
				<a href="#" class="text-decoration-none text-muted">Mentions légales</a>
				<a href="#" class="text-decoration-none text-muted">Confidentialité</a>
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