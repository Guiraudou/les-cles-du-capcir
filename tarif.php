<?php
require_once 'model/config.php';
$meta_title = 'Tarifs conciergerie et transaction immobilière | ' . SITE_NAME;
$meta_description = 'Consultez nos tarifs pour la transaction immobilière et la conciergerie locative au Capcir. Honoraires TTC transparents et dégressifs.';
?>
<?php require_once 'header.inc.php'; ?>

<!-- Hero Section -->
<section class="page-hero">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-8 mx-auto text-center">
				<div class="page-hero-icon mb-3">
					<i class="fa-solid fa-euro-sign"></i>
				</div>
				<h1 class="page-hero-title">Tarifs</h1>
			</div>
		</div>
	</div>
</section>

<!-- Contenu -->
<section>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">

				<!-- Transaction immobilière -->
				<h2 class="h4 fw-bold mb-1">Transaction immobilière</h2>
				<p class="text-muted small mb-4"><strong>Honoraires TTC – TVA incluse au taux en vigueur.</strong> Honoraires à la charge de l'acquéreur, sauf stipulation contraire prévue au mandat.</p>

				<h3 class="h6 fw-bold mb-3">Biens jusqu'à 180 000 € – Forfait</h3>
				<table class="table table-bordered mb-4">
					<thead class="table-dark">
						<tr>
							<th>Prix de vente du bien</th>
							<th class="text-end">Honoraires TTC</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Jusqu'à 50 000 €</td>
							<td class="text-end">2 000 €</td>
						</tr>
						<tr>
							<td>De 50 001 € à 80 000 €</td>
							<td class="text-end">3 000 €</td>
						</tr>
						<tr>
							<td>De 80 001 € à 120 000 €</td>
							<td class="text-end">4 000 €</td>
						</tr>
						<tr>
							<td>De 120 001 € à 180 000 €</td>
							<td class="text-end">5 000 €</td>
						</tr>
					</tbody>
				</table>

				<h3 class="h6 fw-bold mb-3">Biens à partir de 180 001 € – Pourcentage</h3>
				<table class="table table-bordered mb-3">
					<thead class="table-dark">
						<tr>
							<th>Prix de vente du bien</th>
							<th class="text-end">Honoraires TTC</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>De 180 001 € à 250 000 €</td>
							<td class="text-end">5 %</td>
						</tr>
						<tr>
							<td>De 250 001 € à 350 000 €</td>
							<td class="text-end">4,5 %</td>
						</tr>
						<tr>
							<td>De 350 001 € à 450 000 €</td>
							<td class="text-end">4 %</td>
						</tr>
						<tr>
							<td>Au-delà de 450 000 €</td>
							<td class="text-end">3,5 %</td>
						</tr>
					</tbody>
				</table>
				<p class="text-muted small mb-5">Les honoraires comprennent l'ensemble des prestations liées à la transaction immobilière : estimation du bien, diffusion des annonces, visites, négociation et accompagnement administratif et juridique jusqu'à la signature de l'acte authentique.</p>

				<!-- Conciergerie immobilière -->
				<h2 class="h4 fw-bold mb-1">Conciergerie immobilière</h2>
				<p class="text-muted small mb-4"><strong>Honoraires TTC – TVA incluse au taux en vigueur.</strong> Les honoraires de conciergerie sont exprimés en pourcentage du chiffre d'affaires locatif encaissé.</p>

				<table class="table table-bordered mb-3">
					<thead class="table-dark">
						<tr>
							<th>Type de prestation</th>
							<th class="text-end">Honoraires TTC</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Gestion de location saisonnière</td>
							<td class="text-end">Nous consulter *</td>
						</tr>
					</tbody>
				</table>
				<p class="text-muted small mb-2">* Le taux d'honoraires appliqué est déterminé en fonction des caractéristiques du bien, notamment sa typologie, son état général, le niveau de prestation attendu, le prix de la nuitée pratiqué ainsi que la complexité de gestion du logement.</p>
				<p class="text-muted small mb-5">Le taux exact est défini contractuellement avant le début de la mission.</p>

			</div>
		</div>
	</div>
</section>

<?php include 'booking.inc.php'; ?>

<?php require_once 'footer.inc.php'; ?>
