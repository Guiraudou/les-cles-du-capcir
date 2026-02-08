<?php
/**
 * Formulaire de bien réutilisable pour ajout et modification
 * Variable attendue :
 * - $mode : 'add' ou 'edit'
 */
?>
<div class="modal-alert-zone"></div>

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
	<div class="col-md-6 vente-fields">
		<label class="form-label fw-bold">Nombre de personnes :</label>
		<input type="number" class="form-control" name="nb_personnes">
	</div>
	<div class="col-md-6 location-fields">
		<label class="form-label fw-bold">Prix (€) :</label>
		<input type="number" class="form-control" name="prix">
	</div>
</div>

<?php if ($mode === 'edit'): ?>
	<h6 class="mb-3">Images actuelles</h6>
	<div id="edit-images-current" class="mb-3"></div>
<?php endif; ?>

<h6 class="mb-3"><?= $mode === 'edit' ? 'Ajouter des images' : 'Images' ?></h6>
<div class="mb-3">
	<?php if ($mode === 'add'): ?>
		<label class="form-label fw-bold">Photos :</label>
	<?php endif; ?>
	<input type="file" class="form-control" name="images[]" multiple accept="image/*">
	<?php if ($mode === 'add'): ?>
		<div class="form-text">Formats acceptés: JPG, PNG, WEBP (max 5MB)</div>
	<?php endif; ?>
</div>

<div class="<?= $mode === 'edit' ? 'row g-3' : 'mb-3' ?>">
	<div class="<?= $mode === 'edit' ? 'col-md-6' : '' ?>">
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
