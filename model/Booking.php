<?php

use Osimatic\Calendar\SqlDate;
use Osimatic\Data\JsonDB;

/**
 * Calcule la disponibilité et le prix d'un séjour à partir des tarifs Smoobu.
 * Utilisé par api/availability.php (affichage) et api/payment.php (paiement) afin que
 * le montant facturé soit toujours recalculé côté serveur, jamais fourni par le client.
 */
class Booking
{
	private const string PROCESSED_SESSIONS_FILENAME = 'processed_stripe_sessions.json';

	private static ?\Osimatic\API\Smoobu $smoobuClient = null;

	public static function getSmoobuClient(): \Osimatic\API\Smoobu
	{
		return self::$smoobuClient ??= new \Osimatic\API\Smoobu(SMOOBU_API_KEY, SMOOBU_API_SECRET);
	}

	/**
	 * Valide une demande de paiement (apartment_id, dates, coordonnées client).
	 * @throws Exception Si une donnée est invalide
	 */
	public static function validateBookingRequest(array $input): array
	{
		$apartmentId = $input['apartment_id'] ?? '';
		$dateFrom = $input['date_from'] ?? '';
		$dateTo = $input['date_to'] ?? '';
		$titreBien = $input['titre'] ?? 'Séjour Les Clés du Capcir';
		$guestName = trim($input['guest_name'] ?? '');
		$guestEmail = trim($input['guest_email'] ?? '');
		$guestPhone = trim($input['guest_phone'] ?? '');

		if (empty($apartmentId) || !is_numeric($apartmentId)) {
			throw new Exception('Appartement invalide');
		}
		if (!SqlDate::isValid($dateFrom) || !SqlDate::isValid($dateTo) || $dateFrom >= $dateTo || $dateFrom < date('Y-m-d')) {
			throw new Exception('Plage de dates invalide');
		}
		if (empty($guestName) || empty($guestEmail)) {
			throw new Exception('Nom et email obligatoires');
		}
		if (!filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
			throw new Exception('Email invalide');
		}

		return [
			'apartment_id' => (int)$apartmentId,
			'date_from' => $dateFrom,
			'date_to' => $dateTo,
			'guest_name' => $guestName,
			'guest_email' => $guestEmail,
			'guest_phone' => $guestPhone,
			'titre' => $titreBien,
		];
	}

	/**
	 * Valide une demande de vérification de disponibilité (apartment_id, dates).
	 * @throws Exception Si une donnée est invalide
	 */
	public static function validateAvailabilityRequest(string $apartmentId, string $dateFrom, string $dateTo): array
	{
		if (empty($apartmentId) || !is_numeric($apartmentId)) {
			throw new Exception('ID appartement invalide');
		}
		if (empty($dateFrom) || empty($dateTo)) {
			throw new Exception('Dates manquantes');
		}
		if (!SqlDate::isValid($dateFrom) || !SqlDate::isValid($dateTo)) {
			throw new Exception('Format de date invalide (YYYY-MM-DD)');
		}
		if ($dateFrom >= $dateTo) {
			throw new Exception('La date d\'arrivée doit être avant la date de départ');
		}
		if ($dateFrom < date('Y-m-d')) {
			throw new Exception('La date d\'arrivée ne peut pas être dans le passé');
		}

		return [
			'apartment_id' => (int)$apartmentId,
			'date_from' => $dateFrom,
			'date_to' => $dateTo,
		];
	}

	public static function computePrice(int $apartmentId, string $dateFrom, string $dateTo): array
	{
		$smoobu = self::getSmoobuClient();

		$rates = $smoobu->getRates([
			'apartments' => [$apartmentId],
			'start_date' => $dateFrom,
			'end_date' => $dateTo,
		]);

		$apartmentRates = $rates['data'][$apartmentId] ?? null;
		if ($apartmentRates === null) {
			throw new Exception('Appartement introuvable dans Smoobu');
		}

		foreach ($apartmentRates as $dayInfo) {
			if ((int)($dayInfo['available'] ?? 1) === 0) {
				return ['available' => false];
			}
		}

		$dateFromObj = new DateTime($dateFrom);
		$dateToObj = new DateTime($dateTo);
		$nights = $dateFromObj->diff($dateToObj)->days;

		$prixBase = 0;
		$current = clone $dateFromObj;
		for ($i = 0; $i < $nights; $i++) {
			$prixBase += $apartmentRates[$current->format('Y-m-d')]['price'] ?? 0;
			$current->modify('+1 day');
		}

		$prixMajore = ceil($prixBase * (1 + BOOKING_MARKUP_RATE) * 100) / 100; // Arrondi au centime supérieur
		$prixMajoreCents = (int)round($prixMajore * 100); // Pour Stripe (en centimes)

		return [
			'available' => true,
			'nights' => $nights,
			'prix_base' => $prixBase, // Prix Smoobu sans majoration
			'prix_majore' => $prixMajore, // Prix affiché avec majoration
			'prix_cents' => $prixMajoreCents,
			'prix_nuit' => $nights > 0 ? round($prixMajore / $nights, 2) : 0,
		];
	}

	/**
	 * Crée la réservation dans Smoobu une fois le paiement encaissé.
	 * @return int L'identifiant de la réservation Smoobu
	 * @throws Exception Si Smoobu ne retourne pas d'identifiant de réservation
	 */
	public static function createReservation(int $apartmentId, string $dateFrom, string $dateTo, string $firstName, string $lastName, string $guestEmail, string $guestPhone, float $price): int
	{
		$bookingData = [
			'arrivalDate' => $dateFrom,
			'departureDate' => $dateTo,
			'channelId' => SMOOBU_CHANNEL_ID_DIRECT,
			'apartmentId' => $apartmentId,
			'firstName' => $firstName,
			'lastName' => $lastName,
			'email' => $guestEmail,
			'phone' => $guestPhone,
			'adults' => 1,
			'price' => $price, // Prix majoré encaissé
			'priceStatus' => 1, // Payé
			'language' => 'fr',
		];

		$result = self::getSmoobuClient()->createReservation($bookingData);
		$reservationId = $result['id'] ?? null;

		if ($reservationId === null) {
			throw new Exception('Smoobu n\'a pas retourné d\'identifiant de réservation');
		}

		return (int)$reservationId;
	}

	/**
	 * Crée la session de paiement Stripe Checkout pour une demande de réservation validée.
	 */
	public static function createCheckoutSession(array $booking, array $pricing): \Stripe\Checkout\Session
	{
		\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

		$description = sprintf(
			'%s · %s nuit%s · du %s au %s',
			$booking['titre'],
			$pricing['nights'],
			$pricing['nights'] > 1 ? 's' : '',
			(new DateTime($booking['date_from']))->format('d/m/Y'),
			(new DateTime($booking['date_to']))->format('d/m/Y')
		);

		return \Stripe\Checkout\Session::create([
			'payment_method_types' => ['card'],
			'customer_email' => $booking['guest_email'],
			'line_items' => [[
				'price_data' => [
					'currency' => 'eur',
					'unit_amount' => $pricing['prix_cents'],
					'product_data' => [
						'name' => $booking['titre'],
						'description' => $description,
					],
				],
				'quantity' => 1,
			]],
			'mode' => 'payment',
			'success_url' => SITE_URL . '/reservation-confirmee.php?session_id={CHECKOUT_SESSION_ID}',
			'cancel_url' => SITE_URL . '/location.php?annule=1',
			'metadata' => [
				'apartment_id' => $booking['apartment_id'],
				'date_from' => $booking['date_from'],
				'date_to' => $booking['date_to'],
				'guest_name' => $booking['guest_name'],
				'guest_email' => $booking['guest_email'],
				'guest_phone' => $booking['guest_phone'],
				'nights' => $pricing['nights'],
				'titre' => $booking['titre'],
			],
		]);
	}

	/**
	 * Traite le webhook Stripe "checkout.session.completed" : vérifie la signature, l'idempotence,
	 * revérifie la disponibilité, crée la réservation dans Smoobu et notifie le propriétaire par email.
	 * @return array{status: int, body: string} Code HTTP et corps de réponse à renvoyer à Stripe
	 */
	public static function confirmStripePayment(string $payload, string $sigHeader): array
	{
		try {
			$event = \Stripe\Webhook::constructEvent($payload, $sigHeader, STRIPE_WEBHOOK_SECRET);
		} catch (\UnexpectedValueException) {
			return ['status' => 400, 'body' => 'Payload invalide'];
		} catch (\Stripe\Exception\SignatureVerificationException) {
			return ['status' => 400, 'body' => 'Signature invalide'];
		}

		// On ne traite que les paiements réussis
		if ($event->type !== 'checkout.session.completed') {
			return ['status' => 200, 'body' => 'Événement ignoré'];
		}

		$session = $event->data->object;

		if ($session->payment_status !== 'paid') {
			return ['status' => 200, 'body' => 'Paiement non complété'];
		}

		// Idempotence : Stripe peut renvoyer plusieurs fois le même événement
		$sessionsDb = JsonDB::getInstance();
		$processedSessions = $sessionsDb->read(self::PROCESSED_SESSIONS_FILENAME);
		if (in_array($session->id, $processedSessions, true)) {
			return ['status' => 200, 'body' => 'Déjà traité'];
		}

		$apartmentId = $session->metadata->apartment_id ?? null;
		$dateFrom = $session->metadata->date_from ?? null;
		$dateTo = $session->metadata->date_to ?? null;
		$guestName = $session->metadata->guest_name ?? '';
		$guestEmail = $session->metadata->guest_email ?? $session->customer_email ?? '';
		$guestPhone = $session->metadata->guest_phone ?? '';
		$nights = $session->metadata->nights ?? 1;
		$titreBien = $session->metadata->titre ?? 'Séjour';
		$prixPaye = $session->amount_total / 100; // Convertir centimes en euros

		if (!$apartmentId || !$dateFrom || !$dateTo) {
			return ['status' => 400, 'body' => 'Métadonnées manquantes'];
		}

		$nameParts = explode(' ', $guestName, 2);
		$firstName = $nameParts[0] ?? $guestName;
		$lastName = $nameParts[1] ?? '';

		try {
			// Revérifier la disponibilité juste avant de créer la réservation : le bien a pu être
			// réservé par ailleurs (Smoobu, autre canal) entre le paiement Stripe et ce webhook.
			$pricing = self::computePrice((int)$apartmentId, $dateFrom, $dateTo);

			if (!$pricing['available']) {
				$processedSessions[] = $session->id;
				$sessionsDb->write(self::PROCESSED_SESSIONS_FILENAME, $processedSessions);

				error_log("[Booking] Conflit de disponibilité après paiement — session {$session->id}, appartement {$apartmentId}, du {$dateFrom} au {$dateTo}");

				self::sendUnavailabilityAlert($titreBien, $dateFrom, $dateTo, $guestName, $guestEmail, $guestPhone, $prixPaye, $session->id);

				return ['status' => 200, 'body' => json_encode(['success' => false, 'error' => 'Bien non disponible, alerte envoyée'])];
			}

			// Créer la réservation dans Smoobu
			$reservationId = self::createReservation((int)$apartmentId, $dateFrom, $dateTo, $firstName, $lastName, $guestEmail, $guestPhone, $prixPaye);

			$processedSessions[] = $session->id;
			$sessionsDb->write(self::PROCESSED_SESSIONS_FILENAME, $processedSessions);

			self::sendConfirmationEmail($titreBien, $dateFrom, $dateTo, $nights, $guestName, $guestEmail, $guestPhone, $prixPaye, $reservationId, $session->id);

			return ['status' => 200, 'body' => json_encode(['success' => true, 'reservation_id' => $reservationId])];

		} catch (Exception $e) {
			// Logger l'erreur sans bloquer Stripe, et alerter le propriétaire pour un traitement manuel
			error_log('[Booking] Erreur: ' . $e->getMessage());

			self::sendFailureAlert($e->getMessage(), $titreBien, $dateFrom, $dateTo, $guestName, $guestEmail, $guestPhone, $prixPaye, $session->id);

			// On répond 200 pour éviter que Stripe ne renvoie l'événement indéfiniment ; l'email d'alerte permet un traitement manuel
			return ['status' => 200, 'body' => json_encode(['success' => false, 'error' => $e->getMessage()])];
		}
	}

	private static function sendUnavailabilityAlert(string $titreBien, string $dateFrom, string $dateTo, string $guestName, string $guestEmail, string $guestPhone, float $prixPaye, string $stripeSessionId): void
	{
		$bodyHtml = '<p class="alert-text">Un paiement a été encaissé mais le bien n\'est plus disponible pour ces dates (probable double réservation).</p>'
			. Mailer::field('Bien', $titreBien)
			. Mailer::field('Dates', "du {$dateFrom} au {$dateTo}")
			. Mailer::field('Locataire', "{$guestName} ({$guestEmail} / {$guestPhone})")
			. Mailer::field('Montant encaissé', number_format($prixPaye, 2, ',', ' ') . ' €')
			. Mailer::field('Session Stripe', $stripeSessionId)
			. '<p><strong>Action requise :</strong> contacter le locataire et effectuer un remboursement manuel depuis le dashboard Stripe si nécessaire.</p>';

		Mailer::send(
			EMAIL_DESTINATAIRE,
			"Paiement encaissé mais bien indisponible — {$titreBien}",
			Mailer::renderTemplate('Conflit de disponibilité', $bodyHtml, 'Notification automatique du système de réservation en ligne — ' . SITE_NAME),
			$guestEmail ?: null,
			$guestName ?: null
		);
	}

	private static function sendConfirmationEmail(string $titreBien, string $dateFrom, string $dateTo, int|string $nights, string $guestName, string $guestEmail, string $guestPhone, float $prixPaye, int $reservationId, string $stripeSessionId): void
	{
		$bodyHtml = Mailer::field('Bien', $titreBien)
			. Mailer::field('Dates', "du {$dateFrom} au {$dateTo} ({$nights} nuit(s))")
			. Mailer::field('Locataire', $guestName)
			. Mailer::field('Email', '<a href="mailto:' . htmlspecialchars($guestEmail) . '">' . htmlspecialchars($guestEmail) . '</a>', true)
			. Mailer::field('Téléphone', $guestPhone)
			. Mailer::field('Montant encaissé', number_format($prixPaye, 2, ',', ' ') . ' €')
			. Mailer::field('Référence Smoobu', '#' . $reservationId)
			. Mailer::field('Session Stripe', $stripeSessionId);

		Mailer::send(
			EMAIL_DESTINATAIRE,
			"Nouvelle réservation confirmée — {$titreBien}",
			Mailer::renderTemplate('Nouvelle réservation confirmée', $bodyHtml, 'Notification automatique du système de réservation en ligne — ' . SITE_NAME),
			$guestEmail ?: null,
			$guestName ?: null
		);
	}

	private static function sendFailureAlert(string $errorMessage, string $titreBien, string $dateFrom, string $dateTo, string $guestName, string $guestEmail, string $guestPhone, float $prixPaye, string $stripeSessionId): void
	{
		$bodyHtml = '<p class="alert-text">Un paiement a été encaissé mais la création de la réservation dans Smoobu a échoué.</p>'
			. Mailer::field('Erreur', $errorMessage)
			. Mailer::field('Bien', $titreBien)
			. Mailer::field('Dates', "du {$dateFrom} au {$dateTo}")
			. Mailer::field('Locataire', "{$guestName} ({$guestEmail} / {$guestPhone})")
			. Mailer::field('Montant encaissé', number_format($prixPaye, 2, ',', ' ') . ' €')
			. Mailer::field('Session Stripe', $stripeSessionId)
			. '<p><strong>Action requise :</strong> créer la réservation manuellement dans Smoobu.</p>';

		Mailer::send(
			EMAIL_DESTINATAIRE,
			"Échec de création de réservation Smoobu — {$titreBien}",
			Mailer::renderTemplate('Échec de création de réservation', $bodyHtml, 'Notification automatique du système de réservation en ligne — ' . SITE_NAME),
			$guestEmail ?: null,
			$guestName ?: null
		);
	}
}