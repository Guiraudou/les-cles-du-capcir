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
		.section-services{
			padding: 80px 0 40px 0;
		}

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

		.service-box .service-icon svg{
			width: 40px;
			height: 40px;
		}

		/* =================================
		   SECTIONS ET TITRES
		   ================================= */
		section{
			padding: 80px 0;
		}

		.section-title{
			font-weight: 850;
			letter-spacing: .2px;
			font-size: 2rem;
			margin-bottom: 0;
			color: var(--sapin);
		}

		.section-biens{
			padding: 80px 0;
		}

		.section-contact{
			padding: 80px 0;
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
			margin-top: 2rem;
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

		footer .social-link{
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 10px;
			padding: 12px 24px;
			border-radius: 999px;
			background: rgba(255,255,255,.15);
			color: #fff !important;
			text-decoration: none;
			font-weight: 700;
			font-size: 1.05rem;
			border: 2px solid rgba(255,255,255,.3);
			backdrop-filter: blur(10px);
		}

		footer .social-link:hover{
			background: rgba(255,255,255,.25);
			border-color: rgba(255,255,255,.5);
		}

		footer .social-link svg{
			width: 22px;
			height: 22px;
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
						<!-- maison/vente -->
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
							<path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 2 7.5V14a1 1 0 0 0 1 1h3v-4h4v4h3a1 1 0 0 0 1-1V7.5a.5.5 0 0 0 .146-.354.5.5 0 0 0-.146-.354l-6-6z"/>
						</svg>
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
						<!-- calendrier/conciergerie -->
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
							<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
							<path d="M6.445 11.688V6.354h-.633A12.6 12.6 0 0 0 4.5 7.16v.695c.375-.257.969-.62 1.258-.777h.012v4.61h.675zm1.188-1.305c.047.64.594 1.406 1.703 1.406 1.258 0 2-1.066 2-2.871 0-1.934-.781-2.668-1.953-2.668-.926 0-1.797.672-1.797 1.809 0 1.16.824 1.77 1.676 1.77.746 0 1.23-.376 1.383-.79h.027c-.004 1.316-.461 2.164-1.305 2.164-.664 0-1.008-.45-1.05-.82h-.684zm2.953-2.317c0 .696-.559 1.18-1.184 1.18-.601 0-1.144-.383-1.144-1.2 0-.823.582-1.21 1.168-1.21.633 0 1.16.398 1.16 1.23z"/>
						</svg>
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
						<!-- clé -->
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
							<path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/>
							<path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
						</svg>
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
						<!-- t-shirt simple -->
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 32 32">
							<path d="M22 6l4-4 4 4v6h-2v14H4V12H2V6l4-4 4 4h12zm-2 2h-8l-2-2-3 3v1h18V9l-3-3-2 2z"/>
						</svg>
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
			<div class="col-md-3 text-center">
				<img src="logo_white.png" alt="Les clés du Capcir" class="footer-logo">
				<h5 class="fw-bold mb-0">Les clés du Capcir</h5>
				<p class="text-muted small mb-2">Vente & Conciergerie au cœur du Capcir</p>

			</div>
			<div class="col-md-3">
				<h6 class="fw-bold mb-3">Contact</h6>
				<p class="text-muted small mb-2"><strong>Zone :</strong> Capcir & alentours</p>
				<p class="text-muted small mb-2"><strong>Email :</strong> contact@exemple.fr</p>
				<p class="text-muted small mb-2"><strong>Téléphone :</strong> +33 6 00 00 00 00</p>
			</div>
			<div class="col-md-3">
				<h6 class="fw-bold mb-3 footer-heading-alt">Liens</h6>
				<div class="d-flex flex-column gap-2">
					<a href="#top" class="text-decoration-none text-muted small">Accueil</a>
					<a href="#services" class="text-decoration-none text-muted small">Services</a>
					<a href="#biens" class="text-decoration-none text-muted small">Biens</a>
					<a href="#contact" class="text-decoration-none text-muted small">Contact</a>
				</div>
			</div>
			<div class="col-md-3">
				<h6 class="fw-bold mb-3 footer-heading-alt">Réseaux sociaux</h6>
				<a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer" class="social-link">
					<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
						<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
					</svg>
					Instagram
				</a>
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