<!--
	Contenu du calendrier de disponibilités, partagé par la fiche d'un bien (bien.inc.php)
	et le tunnel de réservation (booking.inc.php). Ce fragment ne contient aucun id : il doit
	être inclus dans une div portant un id unique, que JS utilise ensuite comme "bloc calendrier"
	(voir loadCalendrier() dans app.js).
-->
<div class="calendrier-selection-info alert alert-light py-2 mb-2" style="display:none;">
	<i class="fa-solid fa-circle-check me-2 text-success"></i>Arrivée le <strong></strong> — cliquez sur votre date de départ.
</div>
<div class="calendrier-nav">
	<button type="button" class="btn btn-sm btn-outline-sapin" data-cal-nav="prev" onclick="calendrierChangerMois(-1)" aria-label="Mois précédent">
		<i class="fa-solid fa-chevron-left"></i>
	</button>
	<button type="button" class="btn btn-sm btn-outline-sapin" onclick="calendrierChangerMois(1)" aria-label="Mois suivant">
		<i class="fa-solid fa-chevron-right"></i>
	</button>
</div>
<div class="calendrier-dispo">
	<div class="text-center text-muted py-3">
		<div class="spinner-border spinner-border-sm me-2"></div>
		Chargement du calendrier...
	</div>
</div>
<div class="calendrier-legende mt-2">
	<span class="legende-item"><span class="legende-dot dispo"></span> Disponible</span>
	<span class="legende-item"><span class="legende-dot occupe"></span> Occupé</span>
	<span class="legende-item"><span class="legende-dot passe"></span> Passé</span>
	<span class="legende-item"><span class="legende-dot inconnu"></span> Non disponible en ligne</span>
</div>
<p class="text-muted small mt-2 mb-0">Cliquez sur une date d'arrivée puis une date de départ pour réserver directement.</p>