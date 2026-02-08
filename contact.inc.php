<div class="listing contact-form-container">
	<div class="body p-4">
		<form id="contactForm" method="post">
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label" for="nom">Nom <span class="text-danger">*</span></label>
					<input class="form-control" type="text" id="nom" name="nom" placeholder="Votre nom" required>
				</div>
				<div class="col-md-6">
					<label class="form-label" for="email">Email <span class="text-danger">*</span></label>
					<input class="form-control" type="email" id="email" name="email" placeholder="vous@email.fr" required>
				</div>
				<div class="col-md-6">
					<label class="form-label" for="telephone">Téléphone (optionnel)</label>
					<input class="form-control" type="tel" id="telephone" name="telephone" placeholder="+33 ...">
				</div>
				<div class="col-md-6">
					<label class="form-label" for="sujet">Sujet <span class="text-danger">*</span></label>
					<select class="form-select" id="sujet" name="sujet" required>
						<option value="">Choisissez un sujet</option>
						<option value="Vente">Vente</option>
						<option value="Location">Location</option>
						<option value="Conciergerie">Conciergerie</option>
						<option value="Autre">Autre</option>
					</select>
				</div>
				<div class="col-12">
					<label class="form-label" for="message">Message <span class="text-danger">*</span></label>
					<textarea class="form-control" id="message" name="message" rows="4" placeholder="Décrivez votre demande..." required></textarea>
				</div>
				<!-- Honeypot anti-spam (caché) -->
				<input type="text" name="honeypot" style="display:none;" tabindex="-1" autocomplete="off">
				<div class="col-12 text-center">
					<button class="btn btn-sapin" type="submit" id="submitBtn">
						<span id="btnText">Envoyer</span>
						<span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
					</button>
					<div id="formStatus" class="mt-2"></div>
				</div>
			</div>
		</form>
	</div>
</div>