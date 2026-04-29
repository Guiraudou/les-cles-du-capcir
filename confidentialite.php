<?php
require_once 'model/config.php';
?>
<?php require_once 'header.inc.php'; ?>

<!-- Hero Section -->
<section class="page-hero">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-8 mx-auto text-center">
				<div class="page-hero-icon mb-3">
					<i class="fa-solid fa-shield-halved"></i>
				</div>
				<h1 class="page-hero-title">Politique de confidentialité</h1>
			</div>
		</div>
	</div>
</section>

<!-- Contenu -->
<section>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">

				<h2 class="h5 fw-bold mb-2">1. Responsable du traitement</h2>
				<p class="mb-1">LES CLÉS DU CAPCIR – SAS</p>
				<p class="mb-1">Route de la déchetterie, ZAE Capcir, 66210 Matemale</p>
				<p class="mb-4">Email : <a href="mailto:<?= EMAIL_DESTINATAIRE ?>"><?= EMAIL_DESTINATAIRE ?></a></p>

				<h2 class="h5 fw-bold mb-2">2. Données collectées</h2>
				<p class="mb-2">Nous collectons uniquement les données nécessaires :</p>
				<ul class="mb-4">
					<li>identité (nom, prénom)</li>
					<li>coordonnées (email, téléphone)</li>
					<li>informations de réservation</li>
					<li>données techniques (cookies, navigation)</li>
				</ul>

				<h2 class="h5 fw-bold mb-2">3. Finalités</h2>
				<p class="mb-2">Les données sont utilisées pour :</p>
				<ul class="mb-4">
					<li>gestion des réservations</li>
					<li>relation client</li>
					<li>gestion des demandes</li>
					<li>obligations légales et fiscales</li>
				</ul>

				<h2 class="h5 fw-bold mb-2">4. Base légale</h2>
				<ul class="mb-4">
					<li>exécution contractuelle</li>
					<li>obligation légale</li>
					<li>consentement (formulaire / cookies)</li>
				</ul>

				<h2 class="h5 fw-bold mb-2">5. Durée de conservation</h2>
				<ul class="mb-4">
					<li>données clients : 5 ans (obligations contractuelles)</li>
					<li>données de facturation : 10 ans</li>
					<li>données marketing : 3 ans</li>
				</ul>

				<h2 class="h5 fw-bold mb-2">6. Destinataires</h2>
				<p class="mb-2">Les données peuvent être transmises uniquement à :</p>
				<ul class="mb-2">
					<li>prestataires techniques (ex : logiciel de réservation type Smoobu)</li>
					<li>autorités administratives si obligation légale</li>
				</ul>
				<p class="mb-4">Aucune donnée n'est vendue ni cédée.</p>

				<h2 class="h5 fw-bold mb-2">7. Sécurité</h2>
				<p class="mb-4">LES CLÉS DU CAPCIR met en œuvre toutes les mesures techniques et organisationnelles pour protéger les données (HTTPS, accès restreint, etc.).</p>

				<h2 class="h5 fw-bold mb-2">8. Vos droits</h2>
				<p class="mb-2">Conformément au RGPD, vous disposez des droits suivants :</p>
				<ul class="mb-2">
					<li>accès</li>
					<li>rectification</li>
					<li>suppression</li>
					<li>limitation</li>
					<li>opposition</li>
				</ul>
				<p class="mb-2">Contact : <a href="mailto:<?= EMAIL_DESTINATAIRE ?>"><?= EMAIL_DESTINATAIRE ?></a></p>
				<p class="mb-4">Vous pouvez également saisir la <a href="https://www.cnil.fr" target="_blank" rel="noopener noreferrer">CNIL</a>.</p>

				<h2 class="h5 fw-bold mb-2">9. Cookies</h2>
				<p class="mb-2">Le site utilise des cookies pour :</p>
				<ul class="mb-2">
					<li>fonctionnement</li>
					<li>mesure d'audience</li>
					<li>amélioration de l'expérience</li>
				</ul>
				<p class="mb-4">Un bandeau permet de gérer vos préférences.</p>

			</div>
		</div>
	</div>
</section>

<?php include 'booking.inc.php'; ?>

<?php require_once 'footer.inc.php'; ?>