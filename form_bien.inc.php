<?php
/**
 * Formulaire de bien réutilisable pour ajout et modification
 * Variable attendue :
 * - $mode : 'add' ou 'edit'
 */
?>
<div class="alert-zone"></div>

<div class="mb-3">
	<div class="d-flex gap-3 justify-content-center">
		<div class="form-check">
			<label class="form-check-label"><input class="form-check-input" type="radio" name="statut" value="vente"> Vente</label>
		</div>
		<div class="form-check">
			<label class="form-check-label"><input class="form-check-input" type="radio" name="statut" value="location"> Location</label>
		</div>
	</div>
</div>

<!-- Champ ID Smoobu -->
<div class="smoobu-field-container mb-3 hide">
	<label class="form-label fw-bold">ID Smoobu :</label>
	<div class="input-group">
		<input type="text" class="form-control" name="id_smoobu" placeholder="Ex: 123456">
		<button type="button" class="btn btn-primary" id="btn-load-smoobu-<?= $mode ?>">
			<i class="fa-solid fa-download"></i> Charger
		</button>
	</div>
	<small class="text-muted">Entrez l'ID Smoobu pour charger automatiquement les informations du bien</small>
</div>

<div class="row g-3 mb-3">
	<div class="col-12">
		<label class="form-label fw-bold">Titre :</label>
		<input type="text" class="form-control" name="titre" required>
	</div>
	<div class="col-md-6">
		<label class="form-label fw-bold">Lieu :</label>
		<input type="text" class="form-control" name="lieu">
	</div>
	<div class="col-md-6">
		<label class="form-label fw-bold">Surface (m²) :</label>
		<input type="number" step="0.01" class="form-control" name="surface">
	</div>
	<div class="col-12">
		<label class="form-label fw-bold">Description :</label>
		<textarea class="form-control" name="description" rows="3"></textarea>
	</div>
</div>

<div class="row g-3 mb-3">
	<div class="col-md-6">
		<label class="form-label fw-bold">Nombre de chambres :</label>
		<input type="number" class="form-control" name="nb_chambres">
	</div>
	<div class="col-md-6 location-fields">
		<label class="form-label fw-bold">Nombre de personnes :</label>
		<input type="number" class="form-control" name="nb_personnes">
	</div>
	<div class="col-md-6 vente-fields">
		<label class="form-label fw-bold">Prix (€) :</label>
		<input type="number" class="form-control" name="prix">
	</div>
</div>

<div class="mb-3">
	<label class="form-label fw-bold">Photos :</label>

	<!-- Zone de drag & drop -->
	<div class="image-dropzone">
		<i class="fa-solid fa-cloud-arrow-up fa-3x mb-3 text-muted"></i>
		<p class="mb-2">Glissez-déposez vos images ici</p>
		<p class="text-muted small mb-3">ou</p>
		<label class="btn btn-primary btn-sm"><i class="fa-solid fa-folder-open"></i> Parcourir</label>
		<input type="file" name="images[]" class="d-none" multiple accept="image/*">
	</div>

	<div class="form-text mb-3">Formats acceptés: JPG, PNG, WEBP (max 5MB par image)</div>

	<!-- Aperçu des images -->
	<div class="image-preview-container"></div>
</div>

<?php if ($mode === 'edit'): ?>
	<div class="mb-2">Photos actuelles :</div>
	<div id="edit-images-current" class="mb-3"></div>
<?php endif; ?>

<div class="row g-3">
	<div class="col-md-6">
		<label class="form-label fw-bold">Ordre d'affichage :</label>
		<input type="number" class="form-control" name="ordre" value="0">
	</div>
	<?php if ($mode === 'edit'): ?>
		<div class="col-md-6">
			<label class="form-label fw-bold">Statut :</label>
			<select class="form-select" name="actif">
				<option value="1">Actif</option>
				<option value="0">Inactif</option>
			</select>
		</div>
	<?php endif; ?>
</div>
