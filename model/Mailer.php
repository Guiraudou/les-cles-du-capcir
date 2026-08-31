<?php

use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Envoi d'emails HTML via PHPMailer, avec un gabarit commun (model/email/header.inc.php et footer.inc.php)
 * partagé par tous les emails du site (contact, réservations, ...).
 */
class Mailer
{
	/**
	 * Envoie un email HTML.
	 */
	public static function send(string $to, string $subject, string $htmlBody, ?string $replyToEmail = null, ?string $replyToName = null, array $ccEmails = []): bool
	{
		try {
			$mail = new PHPMailer(true);
			$mail->isMail();
			$mail->CharSet = 'UTF-8';
			$mail->setFrom(SENDER_EMAIL, SITE_NAME);
			$mail->addAddress($to);

			foreach ($ccEmails as $ccEmail) {
				$mail->addCC($ccEmail);
			}

			if (!empty($replyToEmail)) {
				$mail->addReplyTo($replyToEmail, $replyToName ?? '');
			}

			$mail->isHTML(true);
			$mail->Subject = $subject;
			$mail->Body = $htmlBody;
			$mail->AltBody = trim(strip_tags(preg_replace('/<br\s*\/?>/i', "\n", $htmlBody)));

			$mail->send();
			return true;
		} catch (PHPMailerException $e) {
			error_log('[Mailer] Échec envoi email vers ' . $to . ' : ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Génère le HTML d'un email à partir du gabarit commun du site (en-tête, pied de page, styles).
	 *
	 * @param string $title Titre affiché dans l'en-tête de l'email
	 * @param string $bodyHtml Contenu HTML du corps de l'email (déjà échappé si nécessaire)
	 * @param string $footerNote Ligne de contexte affichée en pied de page (ex : origine de l'email)
	 */
	public static function renderTemplate(string $title, string $bodyHtml, string $footerNote): string
	{
		$emailTitle = $title;
		$emailFooterNote = $footerNote;

		ob_start();
		require __DIR__ . '/email/header.inc.php';
		echo $bodyHtml;
		require __DIR__ . '/email/footer.inc.php';
		return ob_get_clean();
	}

	/**
	 * Construit un bloc "label / valeur" standard pour le corps d'un email.
	 */
	public static function field(string $label, string $value, bool $isHtml = false): string
	{
		return '
			<div class="field">
				<div class="label">' . htmlspecialchars($label) . ' :</div>
				<div class="value">' . ($isHtml ? $value : nl2br(htmlspecialchars($value))) . '</div>
			</div>';
	}
}