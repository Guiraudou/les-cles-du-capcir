<?php
require_once 'model/config.php';
$meta_title = 'Conditions Générales de Vente | ' . SITE_NAME;
$meta_description = 'Conditions générales de vente de Les Clés du Capcir, agence immobilière et conciergerie au Capcir (Pyrénées-Orientales).';
?>
<?php require_once 'header.inc.php'; ?>

<!-- Hero Section -->
<section class="page-hero">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-8 mx-auto text-center">
				<div class="page-hero-icon mb-3">
					<i class="fa-solid fa-handshake"></i>
				</div>
				<h1 class="page-hero-title">Conditions générales de vente et de location</h1>
			</div>
		</div>
	</div>
</section>

<!-- Contenu -->
<section>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">

				<h2 class="h5 fw-bold mb-2">1. Objet</h2>
				<p class="mb-4">Les présentes conditions encadrent la location de biens immobiliers proposés par LES CLÉS DU CAPCIR, ainsi que les prestations de conciergerie associées.</p>

				<h2 class="h5 fw-bold mb-2">2. Réservation</h2>
				<p class="mb-2">Toute réservation devient ferme après :</p>
				<ul class="mb-2">
					<li>validation écrite</li>
					<li>paiement total ou acompte</li>
				</ul>
				<p class="mb-4">Un contrat ou email de confirmation fait foi.</p>

				<h2 class="h5 fw-bold mb-2">3. Prix</h2>
				<p class="mb-1">Les prix sont exprimés en euros TTC.</p>
				<p class="mb-1">Ils incluent les prestations mentionnées (hébergement, ménage, etc.).</p>
				<p class="mb-4">Des frais supplémentaires peuvent s'appliquer (linge, options…).</p>

				<h2 class="h5 fw-bold mb-2">4. Paiement</h2>
				<p class="mb-2">Le paiement peut être exigé :</p>
				<ul class="mb-2">
					<li>en totalité à la réservation</li>
					<li>ou en deux fois (acompte + solde)</li>
				</ul>
				<p class="mb-4">En cas de non-paiement, la réservation peut être annulée sans préavis.</p>

				<h2 class="h5 fw-bold mb-2">5. Dépôt de garantie (caution)</h2>
				<p class="mb-1">Une caution pourra être demandée.</p>
				<p class="mb-1">Elle couvre les dégradations, pertes ou non-respect du règlement.</p>
				<p class="mb-4">Elle sera restituée sous 15 jours après le séjour, déduction faite des éventuels dommages.</p>

				<h2 class="h5 fw-bold mb-2">6. Annulation</h2>
				<p class="mb-2">Sauf conditions spécifiques :</p>
				<ul class="mb-2">
					<li>Annulation gratuite jusqu'à 15 jours avant arrivée</li>
					<li>Passé ce délai : 100 % du montant reste dû</li>
				</ul>
				<p class="mb-4">En cas de non-présentation : 100 % du séjour est dû.</p>

				<h2 class="h5 fw-bold mb-2">7. Arrivée / départ</h2>
				<ul class="mb-2">
					<li>Check-in : à partir de 17h00</li>
					<li>Check-out : avant 10h00</li>
				</ul>
				<p class="mb-4">Tout dépassement peut entraîner une facturation supplémentaire.</p>

				<h2 class="h5 fw-bold mb-2">8. Utilisation des lieux</h2>
				<p class="mb-2">Le locataire s'engage à :</p>
				<ul class="mb-2">
					<li>respecter la capacité maximale</li>
					<li>maintenir le logement en bon état</li>
					<li>respecter le voisinage (bruit, nuisances)</li>
				</ul>
				<p class="mb-4">Toute fête ou événement non autorisé est interdit.</p>

				<h2 class="h5 fw-bold mb-2">9. Responsabilité</h2>
				<p class="mb-2">LES CLÉS DU CAPCIR agit en tant qu'intermédiaire de gestion.</p>
				<p class="mb-2">La société ne pourra être tenue responsable :</p>
				<ul class="mb-4">
					<li>des vols, pertes ou dégradations d'effets personnels</li>
					<li>des interruptions indépendantes de sa volonté (force majeure, panne réseau, etc.)</li>
				</ul>

				<h2 class="h5 fw-bold mb-2">10. Assurance</h2>
				<p class="mb-4">Le locataire est tenu d'être assuré en responsabilité civile.</p>

				<h2 class="h5 fw-bold mb-2">11. Litiges</h2>
				<p class="mb-2">En cas de litige, une solution amiable sera recherchée en priorité.</p>
				<p class="mb-4">À défaut, compétence exclusive est attribuée aux tribunaux du ressort du siège social de la société.</p>

				<h2 class="h5 fw-bold mb-2">12. Droit applicable</h2>
				<p class="mb-4">Les présentes conditions sont régies par le droit français.</p>

				<h2 class="h5 fw-bold mb-2">13. Acceptation</h2>
				<p class="mb-4">Toute réservation implique l'acceptation pleine et entière des présentes conditions.</p>

			</div>
		</div>
	</div>
</section>

<?php include 'booking.inc.php'; ?>

<?php require_once 'footer.inc.php'; ?>