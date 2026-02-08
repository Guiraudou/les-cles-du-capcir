<?php
require_once 'includes/config.php';

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
	<div id="alert-zone"></div>

	<!-- Liste des biens -->
	<div id="biens-list" class="row g-4">
		<!-- Les biens seront chargés ici en AJAX -->
	</div>
</div>

<!-- Modal Ajout -->
<div class="modal fade" id="modalAdd" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Ajouter un bien</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<form id="formAdd" enctype="multipart/form-data">
				<div class="modal-body">
					<?php
					$mode = 'add';
					include 'form_bien.inc.php';
					?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
					<button type="submit" class="btn btn-success">
						<i class="fa-solid fa-check"></i> Ajouter
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Édition -->
<div class="modal fade" id="modalEdit" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modifier un bien</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<form id="formEdit" enctype="multipart/form-data">
				<input type="hidden" name="id" id="edit-id">
				<div class="modal-body">
					<?php
					$mode = 'edit';
					include 'form_bien.inc.php';
					?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
					<button type="submit" class="btn btn-success">
						<i class="fa-solid fa-check"></i> Enregistrer
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Suppression -->
<div class="modal fade" id="modalDelete" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Supprimer un bien</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body text-center">
				<p>Êtes-vous sûr de vouloir supprimer ce bien ?</p>
				<p class="fw-bold" id="delete-bien-title"></p>
				<p class="text-danger"><i class="fa-solid fa-warning"></i> Cette action est irréversible.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-danger" id="btnConfirmDelete">
					<i class="fa-solid fa-trash"></i> Supprimer
				</button>
			</div>
		</div>
	</div>
</div>

<?php include 'modal_detail_bien.inc.php'; ?>

<?php require_once 'footer.inc.php'; ?>