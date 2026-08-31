<!-- Modal Détails du bien -->
<div class="modal fade" id="modalDetailBien" tabindex="-1">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailBienTitle"></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<!-- Carrousel photos -->
				<div id="carouselDetailBien" class="carousel slide mb-3" data-bs-ride="false">
					<div class="carousel-inner" id="modalDetailImages">
						<!-- Images seront insérées ici -->
					</div>
					<button class="carousel-control-prev" type="button" data-bs-target="#carouselDetailBien" data-bs-slide="prev">
						<span class="carousel-control-prev-icon"></span>
					</button>
					<button class="carousel-control-next" type="button" data-bs-target="#carouselDetailBien" data-bs-slide="next">
						<span class="carousel-control-next-icon"></span>
					</button>
				</div>

				<!-- Vignettes -->
				<div id="modalDetailThumbnails" class="thumbnails-container mb-4">
					<!-- Vignettes seront insérées ici -->
				</div>

				<!-- Prix -->
				<div id="modalDetailPrixContainer" class="prix prix-right text-nowrap mb-3">
					<span id="modalDetailPrix"></span>
				</div>

				<!-- Informations principales -->
				<div class="detail-info-grid mb-4">
					<div class="detail-info-item">
						<i class="fa-solid fa-location-dot text-muted"></i>
						<div>
							<div class="detail-info-label">Lieu</div>
							<div class="detail-info-value" id="modalDetailLieu"></div>
						</div>
					</div>
					<div class="detail-info-item">
						<i class="fa-solid fa-ruler-combined text-muted"></i>
						<div>
							<div class="detail-info-label">Surface</div>
							<div class="detail-info-value" id="modalDetailSurface"></div>
						</div>
					</div>
					<div class="detail-info-item">
						<i class="fa-solid fa-bed text-muted"></i>
						<div>
							<div class="detail-info-label">Chambres</div>
							<div class="detail-info-value" id="modalDetailChambres"></div>
						</div>
					</div>
					<div class="detail-info-item" id="modalDetailPersonnesContainer">
						<i class="fa-solid fa-users text-muted"></i>
						<div>
							<div class="detail-info-label">Personnes</div>
							<div class="detail-info-value" id="modalDetailPersonnes"></div>
						</div>
					</div>
				</div>

				<!-- Description -->
				<div class="detail-description">
					<h6 class="text-muted mb-2">Description</h6>
					<p id="modalDetailDescription" style="white-space: pre-wrap;"></p>
				</div>

				<!-- Calendrier de disponibilités (location uniquement) -->
				<div id="modalDetailCalendrierContainer" style="display:none;" class="mt-4">
					<h6 class="text-muted mb-3"><i class="fa-solid fa-calendar-days me-2"></i>Disponibilités</h6>
					<div id="modalDetailCalendrier" class="calendrier-dispo">
						<div class="text-center text-muted py-3">
							<div class="spinner-border spinner-border-sm me-2"></div>
							Chargement du calendrier...
						</div>
					</div>
					<div class="calendrier-legende mt-2">
						<span class="legende-item"><span class="legende-dot dispo"></span> Disponible</span>
						<span class="legende-item"><span class="legende-dot occupe"></span> Occupé</span>
						<span class="legende-item"><span class="legende-dot passe"></span> Passé</span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
			</div>
		</div>
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
					<?php include 'form_bien.inc.php'; ?>
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
					<?php include 'form_bien.inc.php'; ?>
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

<!-- Modal Confirmation Synchronisation Smoobu -->
<div class="modal fade" id="modalSyncSmoobu" tabindex="-1" aria-labelledby="modalSyncSmoobuLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalSyncSmoobuLabel">
					<i class="fa-solid fa-sync"></i> Synchroniser avec Smoobu
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-info">
					<i class="fa-solid fa-info-circle"></i> Cette opération va :
				</div>
				<ul class="mb-3">
					<li><strong>Créer une sauvegarde</strong> de vos données actuelles</li>
					<li><strong>Ajouter</strong> les nouveaux biens Smoobu</li>
					<li><strong>Mettre à jour</strong> les biens existants (conserve statut actif et ordre)</li>
				</ul>
				<p class="text-muted small mb-0">
					Seule la partie location est synchronisée avec Smoobu.
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					<i class="fa-solid fa-times"></i> Annuler
				</button>
				<button type="button" class="btn btn-info" id="btnConfirmSync">
					<i class="fa-solid fa-sync"></i> Synchroniser
				</button>
			</div>
		</div>
	</div>
</div>
