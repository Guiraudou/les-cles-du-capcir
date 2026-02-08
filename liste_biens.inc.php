<?php
/**
 * Page de liste des biens (vente ou location)
 * Variable attendue: $type ('vente' ou 'location')
 */

if (!isset($type) || !in_array($type, ['vente', 'location'])) {
	die('Type de bien non défini');
}

require_once 'model/config.php';

$bienModel = new Bien();
$biens = $bienModel->getAll($type, true);

$pageTitle = $type === 'vente' ? 'Biens à vendre' : 'Biens en location';
$pageDescription = $type === 'vente'
	? 'Découvrez tous nos biens à vendre dans le Capcir'
	: 'Découvrez tous nos biens en location dans le Capcir';
?>
<?php require_once 'header.inc.php'; ?>

<!-- Hero Section -->
<section class="page-hero <?= $type === 'vente' ? 'page-hero-vente' : 'page-hero-location' ?>">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-8 mx-auto text-center">
				<div class="page-hero-icon mb-3">
					<i class="fa-solid <?= $type === 'vente' ? 'fa-house-circle-check' : 'fa-key' ?>"></i>
				</div>
				<h1 class="page-hero-title"><?= $pageTitle ?></h1>
			</div>
		</div>
	</div>
</section>

<!-- Liste des biens -->
<section class="py-5">
	<div class="container">
		<?php if (empty($biens)): ?>
			<div class="alert alert-info text-center">
				<i class="fa-solid fa-info-circle fa-2x mb-3"></i>
				<p class="mb-0">Aucun bien <?= $type === 'vente' ? 'à vendre' : 'en location' ?> pour le moment.</p>
			</div>
		<?php else: ?>
			<div class="row g-4">
				<?php foreach ($biens as $bien): ?>
					<div class="col-md-6 col-lg-4">
						<div class="listing h-100">
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
									<?php if (!empty($bien['nb_personnes'])): ?>
										<?= (!empty($bien['surface']) || !empty($bien['nb_chambres'])) ? ' • ' : '' ?><?= htmlspecialchars($bien['nb_personnes']) ?> pers.
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
										<?= htmlspecialchars(mb_substr($bien['description'], 0, 100)) ?><?= mb_strlen($bien['description']) > 100 ? '...' : '' ?>
									</p>
								<?php endif; ?>

								<div class="d-flex gap-2">
									<button type="button" class="btn btn-outline-sapin btn-sm" onclick="showDetailModal(<?= $bien['id'] ?>)">
										<i class="fa-solid fa-eye"></i> Détails
									</button>
									<?php if ($bien['statut'] === 'location'): ?>
										<button type="button" class="btn btn-sapin btn-sm" onclick="openBookingModal(<?= !empty($bien['id_smoobu']) ? htmlspecialchars($bien['id_smoobu']) : 0 ?>, '<?= !empty($bien['id_smoobu']) ? htmlspecialchars($bien['titre']) : '' ?>')">
											<i class="fa-solid fa-calendar-check"></i> Réserver
										</button>
									<?php else: ?>
										<a class="btn btn-sapin btn-sm" href="#contact">
											<i class="fa-solid fa-envelope"></i> Contact
										</a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- Section Contact -->
<section id="contact" style="background: var(--bg);">
	<div class="container">
		<h2 class="text-center mb-4">Vous êtes intéressé ?</h2>
		<p class="text-center text-muted mb-4">Contactez-nous pour plus d'informations ou pour organiser une visite.</p>
		<?php include 'contact.inc.php'; ?>
	</div>
</section>

<?php include 'booking.inc.php'; ?>

<?php include 'bien.inc.php'; ?>

<?php require_once 'footer.inc.php'; ?>
