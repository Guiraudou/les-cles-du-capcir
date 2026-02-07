<?php
require_once 'includes/config.php';

$userModel = new User();
$userModel->requireAuth();

?>
<?php require_once 'header.inc.php'; ?>

<!-- Navbar -->
<nav class="navbar navbar-dark mb-4">
	<div class="container-fluid">
		<span class="navbar-brand">
			<i class="fa-solid fa-key"></i> Administration - Les clés du Capcir
		</span>
		<div class="d-flex gap-2">
			<span class="navbar-text text-white me-3">
				<i class="fa-solid fa-user"></i> <?= htmlspecialchars($_SESSION['admin_username']) ?>
			</span>
			<a href="logout.php" class="btn btn-outline-light btn-sm">
				<i class="fa-solid fa-right-from-bracket"></i> Déconnexion
			</a>
		</div>
	</div>
</nav>

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

	<!-- Actions -->
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h2>Gestion des biens</h2>
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
					<h6 class="mb-3">Informations de base</h6>
					<div class="row g-3 mb-3">
						<div class="col-md-6">
							<label class="form-label">Statut <span class="text-danger">*</span></label>
							<select class="form-select" name="statut" id="add-statut" required>
								<option value="">Choisir...</option>
								<option value="vente">Vente</option>
								<option value="location">Location</option>
							</select>
						</div>
						<div class="col-md-6">
							<label class="form-label">Titre <span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="titre" required>
						</div>
						<div class="col-md-6">
							<label class="form-label">Lieu</label>
							<input type="text" class="form-control" name="lieu">
						</div>
						<div class="col-md-6">
							<label class="form-label">Surface (m²)</label>
							<input type="number" step="0.01" class="form-control" name="surface">
						</div>
						<div class="col-12">
							<label class="form-label">Description</label>
							<textarea class="form-control" name="description" rows="3"></textarea>
						</div>
					</div>

					<!-- Champs vente -->
					<div id="add-vente-fields" class="form-section">
						<h6 class="mb-3">Informations vente</h6>
						<div class="row g-3 mb-3">
							<div class="col-md-6">
								<label class="form-label">Prix (€)</label>
								<input type="number" class="form-control" name="prix">
							</div>
							<div class="col-md-6">
								<label class="form-label">Nombre de chambres</label>
								<input type="number" class="form-control" name="nb_chambres">
							</div>
						</div>
					</div>

					<!-- Champs location -->
					<div id="add-location-fields" class="form-section">
						<h6 class="mb-3">Informations location</h6>
						<div class="row g-3 mb-3">
							<div class="col-md-6">
								<label class="form-label">Nombre de personnes</label>
								<input type="number" class="form-control" name="nb_personnes">
							</div>
							<div class="col-md-6">
								<label class="form-label">Nombre de chambres</label>
								<input type="number" class="form-control" name="nb_chambres">
							</div>
						</div>
					</div>

					<h6 class="mb-3">Images</h6>
					<div class="mb-3">
						<label class="form-label">Photos</label>
						<input type="file" class="form-control" name="images[]" multiple accept="image/*">
						<div class="form-text">Formats acceptés: JPG, PNG, WEBP (max 5MB)</div>
					</div>

					<div class="mb-3">
						<label class="form-label">Ordre d'affichage</label>
						<input type="number" class="form-control" name="ordre" value="0">
					</div>
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
					<h6 class="mb-3">Informations de base</h6>
					<div class="row g-3 mb-3">
						<div class="col-md-6">
							<label class="form-label">Statut <span class="text-danger">*</span></label>
							<select class="form-select" name="statut" id="edit-statut" required>
								<option value="vente">Vente</option>
								<option value="location">Location</option>
							</select>
						</div>
						<div class="col-md-6">
							<label class="form-label">Titre <span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="titre" id="edit-titre" required>
						</div>
						<div class="col-md-6">
							<label class="form-label">Lieu</label>
							<input type="text" class="form-control" name="lieu" id="edit-lieu">
						</div>
						<div class="col-md-6">
							<label class="form-label">Surface (m²)</label>
							<input type="number" step="0.01" class="form-control" name="surface" id="edit-surface">
						</div>
						<div class="col-12">
							<label class="form-label">Description</label>
							<textarea class="form-control" name="description" id="edit-description" rows="3"></textarea>
						</div>
					</div>

					<!-- Champs vente -->
					<div id="edit-vente-fields" class="form-section">
						<h6 class="mb-3">Informations vente</h6>
						<div class="row g-3 mb-3">
							<div class="col-md-6">
								<label class="form-label">Prix (€)</label>
								<input type="number" class="form-control" name="prix" id="edit-prix">
							</div>
							<div class="col-md-6">
								<label class="form-label">Nombre de chambres</label>
								<input type="number" class="form-control" name="nb_chambres" id="edit-nb-chambres">
							</div>
						</div>
					</div>

					<!-- Champs location -->
					<div id="edit-location-fields" class="form-section">
						<h6 class="mb-3">Informations location</h6>
						<div class="row g-3 mb-3">
							<div class="col-md-6">
								<label class="form-label">Nombre de personnes</label>
								<input type="number" class="form-control" name="nb_personnes" id="edit-nb-personnes">
							</div>
							<div class="col-md-6">
								<label class="form-label">Nombre de chambres</label>
								<input type="number" class="form-control" name="nb_chambres" id="edit-nb-chambres-loc">
							</div>
						</div>
					</div>

					<h6 class="mb-3">Images actuelles</h6>
					<div id="edit-images-current" class="mb-3"></div>

					<h6 class="mb-3">Ajouter des images</h6>
					<div class="mb-3">
						<input type="file" class="form-control" name="images[]" multiple accept="image/*">
					</div>

					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label">Ordre d'affichage</label>
							<input type="number" class="form-control" name="ordre" id="edit-ordre">
						</div>
						<div class="col-md-6">
							<label class="form-label">Statut</label>
							<select class="form-select" name="actif" id="edit-actif">
								<option value="1">Actif</option>
								<option value="0">Inactif</option>
							</select>
						</div>
					</div>
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

<?php require_once 'footer.inc.php'; ?>
