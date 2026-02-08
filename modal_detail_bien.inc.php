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
					<p id="modalDetailDescription"></p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
