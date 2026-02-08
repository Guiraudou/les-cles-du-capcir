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


				<!-- Informations principales -->
				<div class="row g-3 mb-3">
					<div class="col-md-6">
						<strong><i class="fa-solid fa-location-dot"></i> Lieu :</strong>
						<span id="modalDetailLieu"></span>
					</div>
					<div class="col-md-6">
						<strong><i class="fa-solid fa-ruler-combined"></i> Surface :</strong>
						<span id="modalDetailSurface"></span>
					</div>
					<div class="col-md-6">
						<strong><i class="fa-solid fa-bed"></i> Chambres :</strong>
						<span id="modalDetailChambres"></span>
					</div>
					<div class="col-md-6" id="modalDetailPersonnesContainer">
						<strong><i class="fa-solid fa-users"></i> Personnes :</strong>
						<span id="modalDetailPersonnes"></span>
					</div>
					<div class="col-md-6" id="modalDetailPrixContainer">
						<strong><i class="fa-solid fa-euro-sign"></i> Prix :</strong>
						<span id="modalDetailPrix" class="fs-5 fw-bold"></span>
					</div>
				</div>

				<!-- Description -->
				<div class="mb-3">
					<strong>Description :</strong>
					<p id="modalDetailDescription" class="mt-2"></p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
