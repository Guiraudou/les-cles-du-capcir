<?php

use Osimatic\Calendar\SqlDate;
use Osimatic\Data\JsonDB;

/**
 * Gère tout le cycle de vie d'une réservation :
 * disponibilité, paiement Stripe (acompte 30% + solde 70%),
 * création/mise à jour dans Smoobu, messages automatiques au locataire.
 */
class Booking
{
	private const string PROCESSED_SESSIONS_FILENAME = 'processed_stripe_sessions.json';
	private const string PENDING_BALANCES_FILENAME   = 'pending_balances.json';

	private static ?\Osimatic\API\Smoobu $smoobuClient = null;

	public static function getSmoobuClient(): \Osimatic\API\Smoobu
	{
		return self::$smoobuClient ??= new \Osimatic\API\Smoobu(SMOOBU_API_KEY, SMOOBU_API_SECRET);
	}

	// ================================================================
	// VALIDATION
	// ================================================================

	/** @throws Exception */
	public static function validateBookingRequest(array $input): array
	{
		$apartmentId    = $input['apartment_id'] ?? '';
		$dateFrom       = $input['date_from'] ?? '';
		$dateTo         = $input['date_to'] ?? '';
		$titreBien      = $input['titre'] ?? 'Séjour Les Clés du Capcir';
		$guestFirstname = trim($input['guest_firstname'] ?? '');
		$guestLastname  = trim($input['guest_lastname'] ?? '');
		$guestEmail     = trim($input['guest_email'] ?? '');
		$guestPhone     = trim($input['guest_phone'] ?? '');

		if (empty($apartmentId) || !is_numeric($apartmentId)) {
			throw new Exception('Appartement invalide');
		}
		if (!SqlDate::isValid($dateFrom) || !SqlDate::isValid($dateTo) || $dateFrom >= $dateTo || $dateFrom < date('Y-m-d')) {
			throw new Exception('Plage de dates invalide');
		}
		if (empty($guestFirstname) || empty($guestLastname) || empty($guestEmail)) {
			throw new Exception('Prénom, nom et email obligatoires');
		}
		if (!filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
			throw new Exception('Email invalide');
		}

		return [
			'apartment_id'    => (int)$apartmentId,
			'date_from'       => $dateFrom,
			'date_to'         => $dateTo,
			'guest_firstname' => $guestFirstname,
			'guest_lastname'  => $guestLastname,
			'guest_email'     => $guestEmail,
			'guest_phone'     => $guestPhone,
			'titre'           => $titreBien,
		];
	}

	/** @throws Exception */
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
			'date_from'    => $dateFrom,
			'date_to'      => $dateTo,
		];
	}

	// ================================================================
	// PRIX & DISPONIBILITÉ
	// ================================================================

	public static function computePrice(int $apartmentId, string $dateFrom, string $dateTo): array
	{
		$smoobu = self::getSmoobuClient();

		$rates = $smoobu->getRates([
			'apartments' => [$apartmentId],
			'start_date' => $dateFrom,
			'end_date'   => $dateTo,
		]);

		$apartmentRates = $rates['data'][$apartmentId] ?? null;
		if ($apartmentRates === null) {
			throw new Exception('Appartement introuvable dans Smoobu');
		}

		// On exclut le jour de départ (dateTo) : Smoobu le marque occupé mais c'est
		// le jour de check-out — il doit rester réservable comme arrivée du séjour suivant.
		foreach ($apartmentRates as $date => $dayInfo) {
			if ($date === $dateTo) {
				continue;
			}
			if ((int)($dayInfo['available'] ?? 1) === 0) {
				return ['available' => false];
			}
		}

		$dateFromObj = new DateTime($dateFrom);
		$dateToObj   = new DateTime($dateTo);
		$nights      = $dateFromObj->diff($dateToObj)->days;

		$prixBase = 0;
		$current  = clone $dateFromObj;
		for ($i = 0; $i < $nights; $i++) {
			$prixBase += $apartmentRates[$current->format('Y-m-d')]['price'] ?? 0;
			$current->modify('+1 day');
		}

		$prixMajore      = ceil($prixBase * (1 + BOOKING_MARKUP_RATE) * 100) / 100;
		$prixMajoreCents = (int) round($prixMajore * 100);

		$acompteRate   = BOOKING_DEPOSIT_RATE;
		$acompteMajore = ceil($prixMajore * $acompteRate * 100) / 100;
		$acompteCents  = (int) round($acompteMajore * 100);
		$soldeMajore   = round($prixMajore - $acompteMajore, 2);
		$soldeCents    = (int) round($soldeMajore * 100);

		return [
			'available'     => true,
			'nights'        => $nights,
			'prix_base'     => $prixBase,
			'prix_majore'   => $prixMajore,
			'prix_cents'    => $prixMajoreCents,
			'prix_nuit'     => $nights > 0 ? round($prixMajore / $nights, 2) : 0,
			'acompte_rate'  => $acompteRate,
			'acompte'       => $acompteMajore,
			'acompte_cents' => $acompteCents,
			'solde'         => $soldeMajore,
			'solde_cents'   => $soldeCents,
		];
	}

	// ================================================================
	// SMOOBU — RÉSERVATION
	// ================================================================

	/**
	 * Crée la réservation dans Smoobu avec l'acompte enregistré comme prepayment.
	 * Smoobu affichera : Prix total=X€ / Acompte payé=Y€ / Reste à payer=Z€
	 *
	 * @throws Exception
	 */
	public static function createReservation(
		int $apartmentId, string $dateFrom, string $dateTo,
		string $firstName, string $lastName, string $guestEmail, string $guestPhone,
		float $prixTotal, float $acompte
	): int {
		$bookingData = [
			'arrivalDate'      => $dateFrom,
			'departureDate'    => $dateTo,
			'apartmentId'      => $apartmentId,
			'firstName'        => $firstName,
			'lastName'         => $lastName,
			'email'            => $guestEmail,
			'phone'            => $guestPhone,
			'adults'           => 1,
			'price'            => $prixTotal,   // Prix total majoré
			'priceStatus'      => 0,             // Pas encore entièrement payé
			'prepayment'       => $acompte,      // Acompte encaissé via Stripe
			'prepaymentStatus' => 1,             // Acompte payé
			'language'         => 'fr',
		];

		if (defined('SMOOBU_CHANNEL_ID_DIRECT') && SMOOBU_CHANNEL_ID_DIRECT > 0) {
			$bookingData['channelId'] = SMOOBU_CHANNEL_ID_DIRECT;
		}

		$result        = self::getSmoobuClient()->createReservation($bookingData);
		$reservationId = $result['id'] ?? null;

		if ($reservationId === null) {
			error_log('[Booking::createReservation] Réponse Smoobu : ' . json_encode($result));
			throw new Exception('Smoobu n\'a pas retourné d\'identifiant. Réponse : ' . json_encode($result));
		}

		return (int) $reservationId;
	}

	/**
	 * Met à jour la réservation Smoobu une fois le solde encaissé.
	 * Passe priceStatus à 1 (entièrement payé).
	 */
	public static function markReservationAsPaid(int $reservationId): void
	{
		self::getSmoobuClient()->updateReservation($reservationId, [
			'priceStatus' => 1, // Payé intégralement
		]);
	}

	// ================================================================
	// SMOOBU — MESSAGES
	// ================================================================

	/**
	 * Envoie un message au locataire via l'API Smoobu
	 * (apparaît dans la messagerie Smoobu, sur la réservation).
	 */
	public static function sendSmoobuMessageToGuest(int $reservationId, string $subject, string $body): void
	{
		self::getSmoobuClient()->sendMessageToGuest($reservationId, [
			'subject'     => $subject,
			'messageBody' => $body,
		]);
	}

	// ================================================================
	// STRIPE — SESSION D'ACOMPTE
	// ================================================================

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

		$acomptePct = (int) round($pricing['acompte_rate'] * 100);

		return \Stripe\Checkout\Session::create([
			'payment_method_types' => ['card'],
			'customer_email'       => $booking['guest_email'],
			'line_items'           => [[
				'price_data' => [
					'currency'     => 'eur',
					'unit_amount'  => $pricing['acompte_cents'],
					'product_data' => [
						'name'        => "Acompte {$acomptePct}% — {$booking['titre']}",
						'description' => $description,
					],
				],
				'quantity' => 1,
			]],
			'mode'        => 'payment',
			'success_url' => SITE_URL . '/reservation-confirmee.php?session_id={CHECKOUT_SESSION_ID}',
			'cancel_url'  => SITE_URL . '/location.php?annule=1',
			'metadata'    => [
				'type'            => 'acompte',
				'apartment_id'    => $booking['apartment_id'],
				'date_from'       => $booking['date_from'],
				'date_to'         => $booking['date_to'],
				'guest_firstname' => $booking['guest_firstname'],
				'guest_lastname'  => $booking['guest_lastname'],
				'guest_email'     => $booking['guest_email'],
				'guest_phone'     => $booking['guest_phone'],
				'nights'          => $pricing['nights'],
				'titre'           => $booking['titre'],
				'prix_total'      => $pricing['prix_majore'],
				'acompte'         => $pricing['acompte'],
				'solde'           => $pricing['solde'],
				'solde_cents'     => $pricing['solde_cents'],
			],
		]);
	}

	// ================================================================
	// STRIPE — WEBHOOK (acompte + solde)
	// ================================================================

	/**
	 * Traite le webhook Stripe checkout.session.completed.
	 * Gère à la fois le paiement de l'acompte et du solde.
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

		if ($event->type !== 'checkout.session.completed') {
			return ['status' => 200, 'body' => 'Événement ignoré'];
		}

		$session = $event->data->object;

		if ($session->payment_status !== 'paid') {
			return ['status' => 200, 'body' => 'Paiement non complété'];
		}

		// Idempotence
		$sessionsDb        = JsonDB::getInstance();
		$processedSessions = $sessionsDb->read(self::PROCESSED_SESSIONS_FILENAME) ?: [];
		if (in_array($session->id, $processedSessions, true)) {
			return ['status' => 200, 'body' => 'Déjà traité'];
		}

		$type = $session->metadata->type ?? 'acompte';

		// ── Paiement du solde ──────────────────────────────────────────
		if ($type === 'solde') {
			return self::handleSoldePayment($session, $sessionsDb, $processedSessions);
		}

		// ── Paiement de l'acompte ──────────────────────────────────────
		return self::handleAcomptePayment($session, $sessionsDb, $processedSessions);
	}

	// ── Acompte ────────────────────────────────────────────────────────

	private static function handleAcomptePayment(object $session, JsonDB $sessionsDb, array $processedSessions): array
	{
		$apartmentId = $session->metadata->apartment_id ?? null;
		$dateFrom    = $session->metadata->date_from ?? null;
		$dateTo      = $session->metadata->date_to ?? null;
		$firstName   = $session->metadata->guest_firstname ?? '';
		$lastName    = $session->metadata->guest_lastname ?? '';
		$guestName   = trim("{$firstName} {$lastName}");
		$guestEmail  = $session->metadata->guest_email ?? $session->customer_email ?? '';
		$guestPhone  = $session->metadata->guest_phone ?? '';
		$nights      = $session->metadata->nights ?? 1;
		$titreBien   = $session->metadata->titre ?? 'Séjour';
		$acomptePaye = $session->amount_total / 100;
		$prixTotal   = (float) ($session->metadata->prix_total ?? $acomptePaye);
		$soldeCents  = (int) ($session->metadata->solde_cents ?? 0);
		$solde       = round($soldeCents / 100, 2);

		if (!$apartmentId || !$dateFrom || !$dateTo) {
			return ['status' => 400, 'body' => 'Métadonnées manquantes'];
		}

		try {
			// Revérifier la disponibilité
			$pricing = self::computePrice((int) $apartmentId, $dateFrom, $dateTo);
			if (!$pricing['available']) {
				$processedSessions[] = $session->id;
				$sessionsDb->write(self::PROCESSED_SESSIONS_FILENAME, $processedSessions);
				self::sendUnavailabilityAlert($titreBien, $dateFrom, $dateTo, $guestName, $guestEmail, $guestPhone, $acomptePaye, $session->id);
				return ['status' => 200, 'body' => json_encode(['success' => false, 'error' => 'Bien non disponible'])];
			}

			// Créer la réservation dans Smoobu avec acompte comme prepayment
			$reservationId = self::createReservation(
				(int) $apartmentId, $dateFrom, $dateTo,
				$firstName, $lastName, $guestEmail, $guestPhone,
				$prixTotal, $acomptePaye
			);

			$processedSessions[] = $session->id;
			$sessionsDb->write(self::PROCESSED_SESSIONS_FILENAME, $processedSessions);

			// Enregistrer le solde à envoyer 7 jours avant l'arrivée
			if ($soldeCents > 0) {
				self::savePendingBalance([
					'reservation_id' => $reservationId,
					'apartment_id'   => $apartmentId,
					'titre'          => $titreBien,
					'date_from'      => $dateFrom,
					'date_to'        => $dateTo,
					'guest_name'     => $guestName,
					'guest_email'    => $guestEmail,
					'guest_phone'    => $guestPhone,
					'solde'          => $solde,
					'solde_cents'    => $soldeCents,
					'prix_total'     => $prixTotal,
					'send_on'        => (new DateTime($dateFrom))->modify('-7 days')->format('Y-m-d'),
					'sent'           => false,
					'stripe_session' => $session->id,
				]);
			}

			// Email de confirmation au propriétaire
			self::sendConfirmationEmail(
				$titreBien, $dateFrom, $dateTo, $nights,
				$guestName, $guestEmail, $guestPhone,
				$acomptePaye, $solde, $prixTotal,
				$reservationId, $session->id
			);

			// Message de confirmation au locataire via Smoobu
			$dateFromFr = (new DateTime($dateFrom))->format('d/m/Y');
			$dateToFr   = (new DateTime($dateTo))->format('d/m/Y');
			try {
				self::sendSmoobuMessageToGuest(
					$reservationId,
					"Confirmation de réservation — {$titreBien}",
					"Bonjour {$firstName},\n\n"
					. "Nous avons bien reçu votre acompte de " . number_format($acomptePaye, 2, ',', ' ') . " € "
					. "pour votre séjour à {$titreBien} du {$dateFromFr} au {$dateToFr}.\n\n"
					. "Le solde de " . number_format($solde, 2, ',', ' ') . " € vous sera demandé 7 jours avant votre arrivée par email.\n\n"
					. "À bientôt,\nLes Clés du Capcir"
				);
			} catch (Exception $e) {
				error_log('[Booking] Echec message Smoobu confirmation acompte : ' . $e->getMessage());
			}

			return ['status' => 200, 'body' => json_encode(['success' => true, 'reservation_id' => $reservationId])];

		} catch (Exception $e) {
			error_log('[Booking] Erreur acompte : ' . $e->getMessage());
			self::sendFailureAlert($e->getMessage(), $titreBien, $dateFrom, $dateTo, $guestName, $guestEmail, $guestPhone, $acomptePaye, $session->id);
			return ['status' => 200, 'body' => json_encode(['success' => false, 'error' => $e->getMessage()])];
		}
	}

	// ── Solde ──────────────────────────────────────────────────────────

	private static function handleSoldePayment(object $session, JsonDB $sessionsDb, array $processedSessions): array
	{
		$reservationId = (int) ($session->metadata->reservation_id ?? 0);
		$titreBien     = $session->metadata->titre ?? 'Séjour';
		$dateFrom      = $session->metadata->date_from ?? '';
		$dateTo        = $session->metadata->date_to ?? '';
		$guestEmail    = $session->metadata->guest_email ?? $session->customer_email ?? '';
		$soldePaye     = $session->amount_total / 100;

		try {
			// Marquer la réservation comme intégralement payée dans Smoobu
			if ($reservationId > 0) {
				self::markReservationAsPaid($reservationId);
			}

			$processedSessions[] = $session->id;
			$sessionsDb->write(self::PROCESSED_SESSIONS_FILENAME, $processedSessions);

			// Marquer le solde comme payé dans pending_balances
			self::markBalanceAsPaid($reservationId, $soldePaye);

			// Message au locataire via Smoobu
			if ($reservationId > 0) {
				$dateFromFr = $dateFrom ? (new DateTime($dateFrom))->format('d/m/Y') : '';
				try {
					self::sendSmoobuMessageToGuest(
						$reservationId,
						"Solde reçu — {$titreBien}",
						"Bonjour,\n\n"
						. "Nous avons bien reçu le règlement du solde de " . number_format($soldePaye, 2, ',', ' ') . " € "
						. "pour votre séjour à {$titreBien}."
						. ($dateFromFr ? " Votre réservation est maintenant entièrement réglée. Nous vous attendons le {$dateFromFr} !" : '')
						. "\n\nÀ très bientôt,\nLes Clés du Capcir"
					);
				} catch (Exception $e) {
					error_log('[Booking] Echec message Smoobu confirmation solde : ' . $e->getMessage());
				}
			}

			// Email au propriétaire
			$bodyHtml = '<p>Le solde de la réservation suivante a été encaissé.</p>'
				. Mailer::field('Bien', $titreBien)
				. Mailer::field('Dates', "du {$dateFrom} au {$dateTo}")
				. Mailer::field('Email locataire', htmlspecialchars($guestEmail), true)
				. Mailer::field('Solde encaissé', number_format($soldePaye, 2, ',', ' ') . ' €')
				. Mailer::field('Référence Smoobu', '#' . $reservationId)
				. Mailer::field('Session Stripe', $session->id);

			Mailer::send(
				EMAIL_DESTINATAIRE,
				"Solde encaissé — {$titreBien}",
				Mailer::renderTemplate('Solde encaissé', $bodyHtml, 'Notification automatique — ' . SITE_NAME),
				null, null
			);

			return ['status' => 200, 'body' => json_encode(['success' => true, 'type' => 'solde', 'reservation_id' => $reservationId])];

		} catch (Exception $e) {
			error_log('[Booking] Erreur solde : ' . $e->getMessage());
			return ['status' => 200, 'body' => json_encode(['success' => false, 'error' => $e->getMessage()])];
		}
	}

	// ================================================================
	// SOLDES EN ATTENTE
	// ================================================================

	public static function savePendingBalance(array $data): void
	{
		$db      = JsonDB::getInstance();
		$pending = $db->read(self::PENDING_BALANCES_FILENAME) ?: [];
		$pending[] = $data;
		$db->write(self::PENDING_BALANCES_FILENAME, $pending);
	}

	private static function markBalanceAsPaid(int $reservationId, float $soldePaye): void
	{
		$db      = JsonDB::getInstance();
		$pending = $db->read(self::PENDING_BALANCES_FILENAME) ?: [];
		foreach ($pending as &$entry) {
			if ((int) ($entry['reservation_id'] ?? 0) === $reservationId) {
				$entry['solde_paid']    = true;
				$entry['solde_paid_on'] = date('Y-m-d');
				$entry['solde_paye']    = $soldePaye;
			}
		}
		$db->write(self::PENDING_BALANCES_FILENAME, $pending);
	}

	/**
	 * Envoie les liens de paiement du solde 7 jours avant l'arrivée.
	 * Appelé par cron/solde.php chaque matin.
	 */
	public static function sendPendingBalances(): array
	{
		\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

		$db      = JsonDB::getInstance();
		$pending = $db->read(self::PENDING_BALANCES_FILENAME) ?: [];
		$today   = date('Y-m-d');
		$stats   = ['sent' => 0, 'skipped' => 0, 'errors' => []];

		foreach ($pending as &$entry) {
			if (($entry['sent'] ?? false) || ($entry['send_on'] ?? '9999') > $today) {
				$stats['skipped']++;
				continue;
			}

			try {
				// Créer un Payment Link Stripe pour le solde
				$price = \Stripe\Price::create([
					'currency'     => 'eur',
					'unit_amount'  => $entry['solde_cents'],
					'product_data' => ['name' => "Solde — {$entry['titre']}"],
				]);

				$paymentLink = \Stripe\PaymentLink::create([
					'line_items'       => [['price' => $price->id, 'quantity' => 1]],
					'metadata'         => [
						'type'           => 'solde',
						'reservation_id' => $entry['reservation_id'],
						'titre'          => $entry['titre'],
						'date_from'      => $entry['date_from'],
						'date_to'        => $entry['date_to'],
						'guest_email'    => $entry['guest_email'],
					],
					'after_completion' => [
						'type'     => 'redirect',
						'redirect' => ['url' => SITE_URL . '/merci-solde.php'],
					],
				]);

				$dateFromFr   = (new DateTime($entry['date_from']))->format('d/m/Y');
				$dateToFr     = (new DateTime($entry['date_to']))->format('d/m/Y');
				$soldeFormate = number_format($entry['solde'], 2, ',', ' ') . ' €';
				$lien         = $paymentLink->url;

				// 1. Email direct au locataire
				$bodyHtml = '<p>Bonjour <strong>' . htmlspecialchars($entry['guest_name']) . '</strong>,</p>'
					. '<p>Votre séjour à <strong>' . htmlspecialchars($entry['titre']) . '</strong>'
					. ' du ' . $dateFromFr . ' au ' . $dateToFr . ' approche !</p>'
					. '<p>Le solde de votre réservation d\'un montant de <strong>' . $soldeFormate . '</strong> est maintenant dû.</p>'
					. '<p style="text-align:center;margin:30px 0;">'
					. '<a href="' . $lien . '" style="background:#0f3d2e;color:#fff;padding:14px 28px;border-radius:8px;text-decoration:none;font-weight:bold;">'
					. 'Payer le solde — ' . $soldeFormate . '</a></p>'
					. '<p class="text-muted small">Paiement 100% sécurisé par Stripe.</p>';

				Mailer::send(
					$entry['guest_email'],
					"Solde de votre séjour — {$entry['titre']}",
					Mailer::renderTemplate('Paiement du solde', $bodyHtml, 'Les Clés du Capcir'),
					null, null
				);

				// 2. Message via Smoobu (apparaît dans la messagerie Smoobu)
				$reservationId = (int) ($entry['reservation_id'] ?? 0);
				if ($reservationId > 0) {
					try {
						self::sendSmoobuMessageToGuest(
							$reservationId,
							"Solde de votre séjour — {$entry['titre']}",
							"Bonjour {$entry['guest_name']},\n\n"
							. "Votre séjour à {$entry['titre']} du {$dateFromFr} au {$dateToFr} approche !\n\n"
							. "Le solde de {$soldeFormate} est maintenant dû. Vous pouvez le régler via le lien suivant :\n"
							. $lien . "\n\n"
							. "À très bientôt,\nLes Clés du Capcir"
						);
					} catch (Exception $e) {
						error_log('[Booking::sendPendingBalances] Echec message Smoobu #' . $reservationId . ' : ' . $e->getMessage());
					}
				}

				$entry['sent']         = true;
				$entry['sent_on']      = $today;
				$entry['payment_link'] = $lien;
				$stats['sent']++;

			} catch (Exception $e) {
				$stats['errors'][] = "Réservation #{$entry['reservation_id']} : " . $e->getMessage();
				error_log('[Booking::sendPendingBalances] ' . $e->getMessage());
			}
		}

		$db->write(self::PENDING_BALANCES_FILENAME, $pending);
		return $stats;
	}

	// ================================================================
	// EMAILS
	// ================================================================

	private static function sendUnavailabilityAlert(
		string $titreBien, string $dateFrom, string $dateTo,
		string $guestName, string $guestEmail, string $guestPhone,
		float $prixPaye, string $stripeSessionId
	): void {
		$bodyHtml = '<p class="alert-text">Un paiement a été encaissé mais le bien n\'est plus disponible (probable double réservation).</p>'
			. Mailer::field('Bien', $titreBien)
			. Mailer::field('Dates', "du {$dateFrom} au {$dateTo}")
			. Mailer::field('Locataire', "{$guestName} ({$guestEmail} / {$guestPhone})")
			. Mailer::field('Montant encaissé', number_format($prixPaye, 2, ',', ' ') . ' €')
			. Mailer::field('Session Stripe', $stripeSessionId)
			. '<p><strong>Action requise :</strong> contacter le locataire et effectuer un remboursement manuel depuis le dashboard Stripe.</p>';

		Mailer::send(
			EMAIL_DESTINATAIRE,
			"Paiement encaissé mais bien indisponible — {$titreBien}",
			Mailer::renderTemplate('Conflit de disponibilité', $bodyHtml, 'Notification automatique — ' . SITE_NAME),
			$guestEmail ?: null, $guestName ?: null
		);
	}

	private static function sendConfirmationEmail(
		string $titreBien, string $dateFrom, string $dateTo, int|string $nights,
		string $guestName, string $guestEmail, string $guestPhone,
		float $acomptePaye, float $solde, float $prixTotal,
		int $reservationId, string $stripeSessionId
	): void {
		$bodyHtml = Mailer::field('Bien', $titreBien)
			. Mailer::field('Dates', "du {$dateFrom} au {$dateTo} ({$nights} nuit(s))")
			. Mailer::field('Locataire', $guestName)
			. Mailer::field('Email', '<a href="mailto:' . htmlspecialchars($guestEmail) . '">' . htmlspecialchars($guestEmail) . '</a>', true)
			. Mailer::field('Téléphone', $guestPhone)
			. Mailer::field('Prix total', number_format($prixTotal, 2, ',', ' ') . ' €')
			. Mailer::field('Acompte encaissé (30%)', number_format($acomptePaye, 2, ',', ' ') . ' €')
			. Mailer::field('Solde restant (70%)', number_format($solde, 2, ',', ' ') . ' € — envoyé automatiquement 7j avant l\'arrivée')
			. Mailer::field('Référence Smoobu', '#' . $reservationId)
			. Mailer::field('Session Stripe', $stripeSessionId);

		Mailer::send(
			EMAIL_DESTINATAIRE,
			"Nouvelle réservation confirmée — {$titreBien}",
			Mailer::renderTemplate('Nouvelle réservation confirmée', $bodyHtml, 'Notification automatique — ' . SITE_NAME),
			$guestEmail ?: null, $guestName ?: null
		);
	}

	private static function sendFailureAlert(
		string $errorMessage, string $titreBien, string $dateFrom, string $dateTo,
		string $guestName, string $guestEmail, string $guestPhone,
		float $prixPaye, string $stripeSessionId
	): void {
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
			Mailer::renderTemplate('Échec de création de réservation', $bodyHtml, 'Notification automatique — ' . SITE_NAME),
			$guestEmail ?: null, $guestName ?: null
		);
	}
}
