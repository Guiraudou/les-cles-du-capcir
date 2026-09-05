<!-- Modal de réservation custom (tunnel Stripe) -->
<div class="modal fade" id="modal_booking" tabindex="-1" aria-labelledby="modal_booking_label" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal_booking_label">Réserver votre séjour</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
			</div>
			<div class="modal-body p-4">

				<!-- Étape 1 : Sélection des dates -->
				<div id="booking-step-dates">
					<h6 class="text-muted mb-3"><i class="fa-solid fa-calendar me-2"></i>Choisissez vos dates</h6>
					<div id="booking-calendrier-bloc" class="mb-2">
						<?php include 'calendrier-dispo.inc.php'; ?>
					</div>
					<div id="booking-dispo-result" class="alert mb-3 mt-2" style="display:none;">
						<div class="mb-1">
							<i class="fa-solid fa-circle-check me-2 text-success"></i>
							Arrivée le <strong id="booking-dispo-date-from"></strong> — Départ le <strong id="booking-dispo-date-to"></strong>
						</div>
						<div id="booking-dispo-status"></div>
					</div>
				</div>

				<!-- Étape 2 : Récapitulatif + infos locataire -->
				<div id="booking-step-recap" style="display:none;">
					<h6 class="text-muted mb-3"><i class="fa-solid fa-receipt me-2"></i>Récapitulatif</h6>
					<div id="booking-recap-details" class="alert alert-success mb-2">
						<i class="fa-solid fa-circle-check me-2"></i>
						<strong id="booking-recap-titre"></strong><br>
						<span class="small">Du <strong id="booking-recap-date-from"></strong> au <strong id="booking-recap-date-to"></strong> · <span id="booking-recap-nuits"></span></span><br>
						<span class="fs-5 fw-bold mt-1 d-inline-block" id="booking-recap-prix-total"></span>
						<span class="text-muted small ms-2" id="booking-recap-prix-nuit"></span>
					</div>
					<p class="text-end mb-4">
						<a href="#" class="small" onclick="bookingModifierDates(); return false;"><i class="fa-solid fa-pen me-1"></i>Modifier les dates</a>
					</p>

					<h6 class="text-muted mb-3"><i class="fa-solid fa-user me-2"></i>Vos coordonnées</h6>
					<div class="row g-3 mb-4">
						<div class="col-md-6">
							<label class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
							<input type="text" id="booking-guest-firstname" class="form-control" placeholder="Prénom">
						</div>
						<div class="col-md-6">
							<label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
							<input type="text" id="booking-guest-lastname" class="form-control" placeholder="Nom">
						</div>
						<div class="col-md-6">
							<label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
							<input type="email" id="booking-guest-email" class="form-control" placeholder="votre@email.com">
						</div>
						<div class="col-md-6">
							<label class="form-label fw-semibold">Téléphone</label>
							<input type="tel" id="booking-guest-phone" class="form-control" placeholder="+33 6 00 00 00 00">
						</div>
					</div>

					<div id="booking-error" class="alert alert-danger mb-3" style="display:none;"></div>

					<button id="booking-btn-payer" class="btn btn-sapin w-100 btn-lg" onclick="bookingProcederPaiement()">
						<i class="fa-solid fa-lock me-2"></i>Procéder au paiement sécurisé
					</button>
					<p class="text-muted text-center small mt-2">
						<i class="fa-brands fa-stripe me-1"></i>Paiement 100% sécurisé par Stripe
					</p>
				</div>

			</div>
		</div>
	</div>
</div>

<style>
#modal_booking .modal-header {
	background: linear-gradient(135deg, #0f3d2e 0%, #144939 100%);
	color: white;
	border-bottom: none;
	border-radius: 12px 12px 0 0;
	padding: 1rem 1.5rem;
}
#modal_booking .modal-content {
	border-radius: 12px;
	border: none;
	box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}
#modal_booking .btn-close { filter: brightness(0) invert(1); }
</style>