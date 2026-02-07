<!-- Modal de réservation -->
<div class="modal fade" id="modal_booking" tabindex="-1" aria-labelledby="modal_booking_label" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal_booking_label">Réserver votre séjour</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
			</div>
			<div class="modal-body">
				<div id="apartmentIframeAll">
					<script type="text/javascript" src="https://login.smoobu.com/js/Settings/BookingToolIframe.js"></script>
					<script>BookingToolIframe.initialize({"url": "https://login.smoobu.com/fr/booking-tool/iframe/1436716", "baseUrl": "https://login.smoobu.com", "target": "#apartmentIframeAll"})</script>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
/* Style pour le modal de réservation */
#modal_booking .modal-content {
	border-radius: 12px;
	border: none;
	box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

#modal_booking .modal-header {
	background: linear-gradient(135deg, #0f3d2e 0%, #144939 100%);
	color: white;
	border-bottom: none;
	border-radius: 12px 12px 0 0;
	padding: 1rem 1.5rem;
}

#modal_booking .modal-title {
	font-weight: 600;
	font-size: 1.25rem;
}

#modal_booking .btn-close {
	filter: brightness(0) invert(1);
	opacity: 0.8;
}

#modal_booking .btn-close:hover {
	opacity: 1;
}

#modal_booking .modal-body {
	padding: 0;
	min-height: 500px;
	background: #ffffff;
}

#modal_booking .modal-body #apartmentIframeAll {
	max-width: 1000px;
	margin: 0 auto;
}

#modal_booking {
	width: 100%;
	min-height: 500px;
}

/* Responsive */
@media (max-width: 991px) {
	#modal_booking .modal-dialog {
		margin: 0.5rem;
	}

	#modal_booking .modal-body {
		min-height: 400px;
	}
}
</style>
