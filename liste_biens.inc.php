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
<section class="pb-5">
	<div class="container">

		<?php if ($type === 'location' && !empty($biens)):
			$villes = array_unique(array_filter(array_column($biens, 'city')));
			sort($villes);
		?>
		<!-- Filtres (location uniquement) -->
		<div class="card listing mb-4 p-3">
			<div class="row g-2 align-items-end">
				<!-- Dates -->
				<div class="col-12 col-sm-6 col-lg-2">
					<label for="filter-date-from" class="form-label small fw-bold mb-1"><i class="fa-solid fa-calendar-day me-1"></i>Arrivée</label>
					<input type="date" id="filter-date-from" class="form-control form-control-sm" min="<?= date('Y-m-d') ?>">
				</div>
				<div class="col-12 col-sm-6 col-lg-2">
					<label for="filter-date-to" class="form-label small fw-bold mb-1"><i class="fa-solid fa-calendar-day me-1"></i>Départ</label>
					<input type="date" id="filter-date-to" class="form-control form-control-sm" min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
				</div>
				<!-- Lieu -->
				<div class="col-12 col-sm-6 col-lg-2">
					<label for="filter-city" class="form-label small fw-bold mb-1">Lieu</label>
					<select id="filter-city" class="form-select form-select-sm">
						<option value="">Tous les lieux</option>
						<?php foreach ($villes as $ville): ?>
							<option value="<?= htmlspecialchars($ville) ?>"><?= htmlspecialchars($ville) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<!-- Capacité -->
				<div class="col-12 col-sm-6 col-lg-2">
					<label for="filter-personnes" class="form-label small fw-bold mb-1">Capacité</label>
					<select id="filter-personnes" class="form-select form-select-sm">
						<option value="0">Toutes capacités</option>
						<option value="2">Au moins 2 pers.</option>
						<option value="4">Au moins 4 pers.</option>
						<option value="6">Au moins 6 pers.</option>
						<option value="8">Au moins 8 pers.</option>
					</select>
				</div>
				<!-- Chambres -->
				<div class="col-12 col-sm-6 col-lg-2">
					<label for="filter-chambres" class="form-label small fw-bold mb-1">Chambres</label>
					<select id="filter-chambres" class="form-select form-select-sm">
						<option value="0">Toutes chambres</option>
						<option value="1">Au moins 1</option>
						<option value="2">Au moins 2</option>
						<option value="3">Au moins 3</option>
						<option value="4">Au moins 4</option>
					</select>
				</div>
				<!-- Boutons -->
				<div class="col-12 col-sm-6 col-lg-2 d-flex align-items-end gap-2">
					<button type="button" id="btn-recherche-dates" class="btn btn-sapin btn-sm flex-grow-1" disabled>
						<i class="fa-solid fa-magnifying-glass"></i> Rechercher
					</button>
					<button type="button" id="reset-filters" class="btn btn-outline-secondary btn-sm">
						<i class="fa-solid fa-xmark"></i>
					</button>
				</div>
			</div>
			<!-- Barre de statut -->
			<div class="border-top mt-3 pt-2 text-center small text-muted">
				<span id="filter-count"></span>
				<span id="filter-loading" class="d-none">
					<span class="spinner-border spinner-border-sm me-1" role="status"></span>
					Vérification des disponibilités en cours…
				</span>
			</div>
			<div id="no-results" class="alert alert-warning mt-3 mb-0 d-none">
				<i class="fa-solid fa-triangle-exclamation"></i> Aucun bien ne correspond à ces critères.
			</div>
			<div id="filter-date-error" class="alert alert-danger mt-3 mb-0 d-none">
				<i class="fa-solid fa-circle-exclamation"></i> <span id="filter-date-error-msg"></span>
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
						data-chambres="<?= intval($bien['nb_chambres'] ?? 0) ?>"
						data-smoobu-id="<?= intval($bien['id_smoobu'] ?? 0) ?>">
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
	const filterDateFrom  = document.getElementById('filter-date-from');
	const filterDateTo    = document.getElementById('filter-date-to');
	const filterCity      = document.getElementById('filter-city');
	const filterPersonnes = document.getElementById('filter-personnes');
	const filterChambres  = document.getElementById('filter-chambres');
	const resetBtn        = document.getElementById('reset-filters');
	const searchBtn       = document.getElementById('btn-recherche-dates');
	const countLabel      = document.getElementById('filter-count');
	const loadingEl       = document.getElementById('filter-loading');
	const noResults       = document.getElementById('no-results');
	const dateError       = document.getElementById('filter-date-error');
	const dateErrorMsg    = document.getElementById('filter-date-error-msg');
	const cards           = document.querySelectorAll('.filter-card');

	// IDs Smoobu par carte (data-smoobu-id sur chaque carte)
	// Ensemble des IDs disponibles après requête API (null = pas encore filtré par dates)
	let disponiblesSmoobu = null;

	// Active/désactive le bouton Rechercher selon les dates
	function updateSearchBtn() {
		const from = filterDateFrom ? filterDateFrom.value : '';
		const to   = filterDateTo   ? filterDateTo.value   : '';
		if (searchBtn) searchBtn.disabled = !(from && to && from < to);
	}

	// Applique les filtres locaux (ville, capacité, chambres) + dispo Smoobu si chargée
	function applyFilters() {
		const city      = filterCity      ? filterCity.value            : '';
		const personnes = filterPersonnes ? parseInt(filterPersonnes.value) : 0;
		const chambres  = filterChambres  ? parseInt(filterChambres.value)  : 0;
		let visible = 0;

		cards.forEach(function (card) {
			let show = true;

			// Filtre ville
			if (city && card.dataset.city !== city) show = false;

			// Filtre capacité
			if (personnes > 0 && (parseInt(card.dataset.personnes) || 0) < personnes) show = false;

			// Filtre chambres
			if (chambres > 0 && (parseInt(card.dataset.chambres) || 0) < chambres) show = false;

			// Filtre dispo dates (si une recherche a été faite)
			if (disponiblesSmoobu !== null) {
				const smoobuId = parseInt(card.dataset.smoobuId) || 0;
				if (smoobuId && !disponiblesSmoobu.includes(smoobuId)) show = false;
			}

			card.style.display = show ? '' : 'none';
			if (show) visible++;
		});

		const dateFrom = filterDateFrom ? filterDateFrom.value : '';
		const dateTo   = filterDateTo   ? filterDateTo.value   : '';
		const hasDateFilter = disponiblesSmoobu !== null && dateFrom && dateTo;
		const hasOtherFilter = (filterCity && filterCity.value) ||
			(filterPersonnes && parseInt(filterPersonnes.value) > 0) ||
			(filterChambres && parseInt(filterChambres.value) > 0);
		const isFiltered = hasDateFilter || hasOtherFilter;

		if (countLabel) {
			const label = '<strong>' + visible + ' bien' + (visible !== 1 ? 's' : '') + '</strong>';
			if (hasDateFilter) {
				const d1 = new Date(dateFrom + 'T00:00:00').toLocaleDateString('fr-FR', {day:'2-digit', month:'short'});
				const d2 = new Date(dateTo   + 'T00:00:00').toLocaleDateString('fr-FR', {day:'2-digit', month:'short'});
				countLabel.innerHTML = '<i class="fa-solid fa-check-circle text-success me-1"></i>' + label + ' disponible' + (visible !== 1 ? 's' : '') + ' du ' + d1 + ' au ' + d2;
			} else if (isFiltered) {
				countLabel.innerHTML = '<i class="fa-solid fa-filter me-1"></i>' + label + ' selon ces critères';
			} else {
				countLabel.innerHTML = '<i class="fa-solid fa-house me-1"></i>' + label + ' disponible' + (visible !== 1 ? 's' : '');
			}
		}
		if (noResults) noResults.classList.toggle('d-none', visible > 0);
	}

	// Recherche de dispo via API
	async function rechercherDispos() {
		const dateFrom = filterDateFrom ? filterDateFrom.value : '';
		const dateTo   = filterDateTo   ? filterDateTo.value   : '';

		if (!dateFrom || !dateTo || dateFrom >= dateTo) return;

		// Cacher l'erreur précédente
		if (dateError) dateError.classList.add('d-none');

		// Afficher loader
		if (loadingEl)  loadingEl.classList.remove('d-none');
		if (countLabel) countLabel.textContent = '';
		if (searchBtn)  searchBtn.disabled = true;

		try {
			const params = new URLSearchParams({ date_from: dateFrom, date_to: dateTo });
			const resp = await fetch('api/biens_dispos.php?' + params.toString());
			const data = await resp.json();

			if (!data.success) {
				throw new Error(data.error || 'Erreur serveur');
			}

			disponiblesSmoobu = data.disponibles || [];
			applyFilters();

		} catch (e) {
			if (dateError && dateErrorMsg) {
				dateErrorMsg.textContent = e.message || 'Impossible de vérifier les disponibilités. Réessayez.';
				dateError.classList.remove('d-none');
			}
			disponiblesSmoobu = null;
			applyFilters();
		} finally {
			if (loadingEl) loadingEl.classList.add('d-none');
			updateSearchBtn();
		}
	}

	// Écouteurs filtres locaux
	if (filterCity)      filterCity.addEventListener('change', applyFilters);
	if (filterPersonnes) filterPersonnes.addEventListener('change', applyFilters);
	if (filterChambres)  filterChambres.addEventListener('change', applyFilters);

	// Écouteurs dates → active/désactive le bouton
	if (filterDateFrom) {
		filterDateFrom.addEventListener('change', function () {
			// Ajuster le min du départ
			if (filterDateTo && this.value) {
				const nextDay = new Date(this.value + 'T00:00:00');
				nextDay.setDate(nextDay.getDate() + 1);
				filterDateTo.min = nextDay.toISOString().split('T')[0];
				if (filterDateTo.value && filterDateTo.value <= this.value) {
					filterDateTo.value = nextDay.toISOString().split('T')[0];
				}
			}
			// Si on change les dates, reset les dispos
			disponiblesSmoobu = null;
			applyFilters();
			updateSearchBtn();
		});
	}
	if (filterDateTo) {
		filterDateTo.addEventListener('change', function () {
			disponiblesSmoobu = null;
			applyFilters();
			updateSearchBtn();
		});
	}

	// Bouton Rechercher
	if (searchBtn) searchBtn.addEventListener('click', rechercherDispos);

	// Reset
	if (resetBtn) {
		resetBtn.addEventListener('click', function () {
			if (filterDateFrom)  filterDateFrom.value  = '';
			if (filterDateTo)    filterDateTo.value    = '';
			if (filterCity)      filterCity.value      = '';
			if (filterPersonnes) filterPersonnes.value = '0';
			if (filterChambres)  filterChambres.value  = '0';
			if (dateError)       dateError.classList.add('d-none');
			disponiblesSmoobu = null;
			updateSearchBtn();
			applyFilters();
		});
	}

	// Init
	updateSearchBtn();
	applyFilters();
})();
</script>
<?php endif; ?>

<?php require_once 'footer.inc.php'; ?>
