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

$meta_title = $type === 'vente'
	? 'Biens immobiliers à vendre au Capcir | ' . SITE_NAME
	: 'Locations saisonnières au Capcir | ' . SITE_NAME;
$meta_description = $type === 'vente'
	? 'Achetez un bien immobilier au Capcir : chalets et appartements à Les Angles, Formiguères, Puyvalador. Honoraires transparents. Contactez-nous.'
	: 'Découvrez nos locations saisonnières au Capcir : chalets et appartements à Les Angles, Formiguères, Puyvalador. Réservez en ligne.';
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

		<?php if ($type === 'location' && !empty($biens)):
			$villes = array_unique(array_filter(array_column($biens, 'city')));
			sort($villes);
		?>
		<!-- Filtres (location uniquement) -->
		<div class="card border-0 shadow-sm mb-4 p-3">
			<div class="row g-2 align-items-end">
				<div class="col-12 col-sm-6 col-lg-3">
					<label for="filter-city" class="form-label small fw-bold mb-1">Lieu</label>
					<select id="filter-city" class="form-select form-select-sm">
						<option value="">Tous les lieux</option>
						<?php foreach ($villes as $ville): ?>
							<option value="<?= htmlspecialchars($ville) ?>"><?= htmlspecialchars($ville) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-12 col-sm-6 col-lg-3">
					<label for="filter-personnes" class="form-label small fw-bold mb-1">Capacité</label>
					<select id="filter-personnes" class="form-select form-select-sm">
						<option value="0">Toutes capacités</option>
						<option value="2">Au moins 2 pers.</option>
						<option value="4">Au moins 4 pers.</option>
						<option value="6">Au moins 6 pers.</option>
						<option value="8">Au moins 8 pers.</option>
					</select>
				</div>
				<div class="col-12 col-sm-6 col-lg-3">
					<label for="filter-surface" class="form-label small fw-bold mb-1">Surface</label>
					<select id="filter-surface" class="form-select form-select-sm">
						<option value="">Toutes surfaces</option>
						<option value="small">Moins de 30 m²</option>
						<option value="medium">30 à 60 m²</option>
						<option value="large">Plus de 60 m²</option>
					</select>
				</div>
				<div class="col-12 col-sm-6 col-lg-3 d-flex align-items-end gap-2">
					<button type="button" id="reset-filters" class="btn btn-outline-secondary btn-sm">
						<i class="fa-solid fa-xmark"></i> Effacer
					</button>
					<span id="filter-count" class="text-muted small"></span>
				</div>
			</div>
			<div id="no-results" class="alert alert-warning mt-3 mb-0 d-none">
				<i class="fa-solid fa-triangle-exclamation"></i> Aucun bien ne correspond à ces critères.
			</div>
		</div>
		<?php endif; ?>

		<?php if (empty($biens)): ?>
			<div class="alert alert-info text-center">
				<i class="fa-solid fa-info-circle fa-2x mb-3"></i>
				<p class="mb-0">Aucun bien <?= $type === 'vente' ? 'à vendre' : 'en location' ?> pour le moment.</p>
			</div>
		<?php else: ?>
			<div id="biens-grid" class="row g-4">
				<?php foreach ($biens as $bien): ?>
					<div class="col-md-6 col-lg-4 filter-card"
						data-city="<?= htmlspecialchars($bien['city'] ?? '') ?>"
						data-personnes="<?= intval($bien['nb_personnes'] ?? 0) ?>"
						data-surface="<?= floatval($bien['surface'] ?? 0) ?>">
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
									<?php if (!empty($bien['city'])): ?>
										<i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($bien['city']) ?>
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

<?php if ($type === 'location'): ?>
<script>
(function () {
	const filterCity     = document.getElementById('filter-city');
	const filterPersonnes = document.getElementById('filter-personnes');
	const filterSurface  = document.getElementById('filter-surface');
	const resetBtn       = document.getElementById('reset-filters');
	const countLabel     = document.getElementById('filter-count');
	const noResults      = document.getElementById('no-results');
	const cards          = document.querySelectorAll('.filter-card');

	function applyFilters() {
		const city     = filterCity ? filterCity.value : '';
		const personnes = filterPersonnes ? parseInt(filterPersonnes.value) : 0;
		const surface  = filterSurface ? filterSurface.value : '';
		let visible = 0;

		cards.forEach(function (card) {
			let show = true;

			if (city && card.dataset.city !== city) {
				show = false;
			}

			if (personnes > 0) {
				const cardPersonnes = parseInt(card.dataset.personnes) || 0;
				if (cardPersonnes < personnes) show = false;
			}

			if (surface) {
				const s = parseFloat(card.dataset.surface) || 0;
				if (surface === 'small'  && s >= 30) show = false;
				if (surface === 'medium' && (s < 30 || s > 60)) show = false;
				if (surface === 'large'  && s <= 60) show = false;
			}

			card.style.display = show ? '' : 'none';
			if (show) visible++;
		});

		if (countLabel) {
			countLabel.textContent = visible + ' bien' + (visible !== 1 ? 's' : '');
		}
		if (noResults) {
			noResults.classList.toggle('d-none', visible > 0);
		}
	}

	if (filterCity)     filterCity.addEventListener('change', applyFilters);
	if (filterPersonnes) filterPersonnes.addEventListener('change', applyFilters);
	if (filterSurface)  filterSurface.addEventListener('change', applyFilters);

	if (resetBtn) {
		resetBtn.addEventListener('click', function () {
			if (filterCity)     filterCity.value = '';
			if (filterPersonnes) filterPersonnes.value = '0';
			if (filterSurface)  filterSurface.value = '';
			applyFilters();
		});
	}

	// Compteur initial
	if (countLabel) {
		countLabel.textContent = cards.length + ' bien' + (cards.length !== 1 ? 's' : '');
	}
})();
</script>
<?php endif; ?>

<?php require_once 'footer.inc.php'; ?>
