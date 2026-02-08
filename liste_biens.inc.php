<?php
/**
 * Page de liste des biens (vente ou location)
 * Variable attendue: $type ('vente' ou 'location')
 */

if (!isset($type) || !in_array($type, ['vente', 'location'])) {
	die('Type de bien non défini');
}

require_once 'includes/config.php';

$bienModel = new Bien();
$biens = $bienModel->getAll($type, true);

$pageTitle = $type === 'vente' ? 'Biens à vendre' : 'Biens en location';
$pageDescription = $type === 'vente'
	? 'Découvrez tous nos biens à vendre dans le Capcir'
	: 'Découvrez tous nos biens en location dans le Capcir';
?>
<?php require_once 'header.inc.php'; ?>

<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, <?= $type === 'vente' ? '#1e40af' : '#059669' ?> 0%, <?= $type === 'vente' ? '#3b82f6' : '#10b981' ?> 100%); padding: 80px 0;">
	<div class="container text-center text-white">
		<h1 class="display-4 fw-bold mb-3"><?= $pageTitle ?></h1>
		<p class="lead"><?= $pageDescription ?></p>
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
							<?php if (!empty($bien['images'])): ?>
								<img src="<?= htmlspecialchars($bien['images'][0]['url']) ?>" alt="<?= htmlspecialchars($bien['titre']) ?>">
							<?php else: ?>
								<div class="listing-image-placeholder bg-secondary d-flex align-items-center justify-content-center text-white">
									<i class="fa-solid fa-image fa-3x opacity-50"></i>
								</div>
							<?php endif; ?>
							<div class="body">
								<h5 class="fw-bold mb-2"><?= htmlspecialchars($bien['titre']) ?></h5>

								<div class="text-muted small mb-3">
									<?php if (!empty($bien['lieu'])): ?>
										<i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($bien['lieu']) ?>
									<?php endif; ?>
									<?php if (!empty($bien['surface'])): ?>
										<?= !empty($bien['lieu']) ? ' • ' : '' ?><?= htmlspecialchars($bien['surface']) ?> m²
									<?php endif; ?>
									<?php if (!empty($bien['nb_chambres'])): ?>
										• <?= htmlspecialchars($bien['nb_chambres']) ?> ch.
									<?php endif; ?>
									<?php if ($type === 'location' && !empty($bien['nb_personnes'])): ?>
										• <?= htmlspecialchars($bien['nb_personnes']) ?> pers.
									<?php endif; ?>
								</div>

								<?php if (!empty($bien['description'])): ?>
									<p class="small text-muted mb-3">
										<?= htmlspecialchars(mb_substr($bien['description'], 0, 100)) ?><?= mb_strlen($bien['description']) > 100 ? '...' : '' ?>
									</p>
								<?php endif; ?>

								<?php if (!empty($bien['prix'])): ?>
									<div class="fw-bold fs-5 mb-3" style="color: <?= $type === 'vente' ? '#1e40af' : '#059669' ?>;">
										<?= number_format($bien['prix'], 0, ',', ' ') ?> €<?= $type === 'location' ? '/semaine' : '' ?>
									</div>
								<?php endif; ?>

								<div class="d-flex gap-2">
									<button type="button" class="btn btn-outline-sapin btn-sm" onclick="showDetailModal(<?= $bien['id'] ?>)">
										<i class="fa-solid fa-eye"></i> Détails
									</button>
									<a class="btn btn-sapin btn-sm" href="#contact">
										<i class="fa-solid fa-envelope"></i> Contact
									</a>
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
<section id="contact" class="py-5" style="background: var(--bg);">
	<div class="container">
		<h2 class="text-center mb-4">Vous êtes intéressé ?</h2>
		<p class="text-center text-muted mb-4">Contactez-nous pour plus d'informations ou pour organiser une visite.</p>
		<?php include 'contact.inc.php'; ?>
	</div>
</section>

<?php include 'modal_detail_bien.inc.php'; ?>

<?php require_once 'footer.inc.php'; ?>
