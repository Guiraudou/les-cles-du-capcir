<?php
require_once 'model/config.php';
$meta_title = 'Mentions légales | ' . SITE_NAME;
$meta_description = 'Mentions légales du site Les Clés du Capcir, agence immobilière et conciergerie au Capcir.';
?>
<?php require_once 'header.inc.php'; ?>

<!-- Hero Section -->
<section class="page-hero">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-8 mx-auto text-center">
				<div class="page-hero-icon mb-3">
					<i class="fa-solid fa-file-contract"></i>
				</div>
				<h1 class="page-hero-title">Mentions légales</h1>
			</div>
		</div>
	</div>
</section>

<!-- Contenu -->
<section>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<h2 class="h5 fw-bold mb-2">Éditeur du site</h2>
				<p class="mb-1">LES CLÉS DU CAPCIR – SAS</p>
				<p class="mb-1">Capital social : 6 000 €</p>
				<p class="mb-1">Siège social : Route de la déchetterie, ZAE Capcir, 66210 Matemale</p>
				<p class="mb-1">SIRET : 991 000 381 00012</p>
				<p class="mb-1">RCS Perpignan : 991 000 381</p>
				<p class="mb-1">TVA intracommunautaire : FR43991000381</p>
				<p class="mb-1">Téléphone : <a href="tel:<?= PHONE ?>"><?= \Osimatic\Messaging\PhoneNumber::formatInternational(PHONE) ?></a></p>
				<p class="mb-4">Email : <a href="mailto:<?= EMAIL_DESTINATAIRE ?>"><?= EMAIL_DESTINATAIRE ?></a></p>

				<h2 class="h5 fw-bold mb-2">Directeur de la publication</h2>
				<p class="mb-4">Camille Milhau</p>

				<h2 class="h5 fw-bold mb-2">Hébergement</h2>
				<p class="mb-1">OVH SAS</p>
				<p class="mb-1">2 rue Kellermann – 59100 Roubaix – France</p>
				<p class="mb-1">Téléphone : 1007</p>
				<p class="mb-4"><a href="https://www.ovh.com" target="_blank" rel="noopener noreferrer">www.ovh.com</a></p>

				<h2 class="h5 fw-bold mb-2">Propriété intellectuelle</h2>
				<p class="mb-2">L'ensemble du site (structure, textes, images, logos, etc.) est protégé par le droit de la propriété intellectuelle.</p>
				<p class="mb-4">Toute reproduction, représentation ou exploitation, totale ou partielle, sans autorisation écrite préalable est strictement interdite.</p>

				<h2 class="h5 fw-bold mb-2">Responsabilité</h2>
				<p class="mb-2">LES CLÉS DU CAPCIR s'efforce d'assurer l'exactitude des informations diffusées sur le site, mais ne saurait être tenue responsable des omissions, inexactitudes ou carences dans la mise à jour.</p>
				<p class="mb-4">Le site peut contenir des liens vers des sites tiers. La société ne peut être tenue responsable du contenu de ces sites.</p>
			</div>
		</div>
	</div>
</section>

<?php include 'booking.inc.php'; ?>

<?php require_once 'footer.inc.php'; ?>
