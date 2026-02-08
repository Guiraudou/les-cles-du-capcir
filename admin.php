<?php
require_once 'model/config.php';

$userModel = new User();
$userModel->requireAuth();

?>
<?php require_once 'header.inc.php'; ?>

<div class="container">
	<!-- Stats -->
	<div class="row mb-4">
		<div class="col-md-4">
			<div class="card stat-card">
				<div class="card-body">
					<h5 class="card-title text-muted">Total biens</h5>
					<p class="fs-2 fw-bold mb-0" id="stat-total">0</p>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card stat-card">
				<div class="card-body">
					<h5 class="card-title text-muted">Ventes actives</h5>
					<p class="fs-2 fw-bold mb-0 text-primary" id="stat-ventes">0</p>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card stat-card">
				<div class="card-body">
					<h5 class="card-title text-muted">Locations actives</h5>
					<p class="fs-2 fw-bold mb-0 text-success" id="stat-locations">0</p>
				</div>
			</div>
		</div>
	</div>

	<!-- Filtres / Actions -->
	<div class="d-flex justify-content-between align-items-center mb-3">
		<div class="btn-group" role="group">
			<button type="button" class="btn btn-outline-primary active" data-filter="all">
				Tous
			</button>
			<button type="button" class="btn btn-outline-primary" data-filter="vente">
				Ventes
			</button>
			<button type="button" class="btn btn-outline-primary" data-filter="location">
				Locations
			</button>
		</div>

		<button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">
			<i class="fa-solid fa-plus"></i> Ajouter un bien
		</button>
	</div>

	<!-- Alert zone -->
	<div class="alert-zone"></div>

	<!-- Liste des biens -->
	<div id="biens-list" class="row g-4">
		<!-- Les biens seront chargés ici en AJAX -->
	</div>
</div>

<?php include 'bien.inc.php'; ?>

<?php require_once 'footer.inc.php'; ?>