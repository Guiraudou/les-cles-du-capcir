<?php
/**
 * cron/solde.php
 * Tâche CRON à exécuter chaque jour à 9h00.
 * Envoie automatiquement les liens de paiement du solde aux locataires
 * dont l'arrivée est dans 7 jours, via email ET message Smoobu.
 *
 * Configuration CRON sur le serveur (cPanel ou SSH) :
 *   0 9 * * * php /chemin/vers/site/cron/solde.php >> /chemin/vers/site/cron/solde.log 2>&1
 *
 * Ou via URL sécurisée (si CLI non disponible) :
 *   https://lesclesducapcir.fr/cron/solde.php?token=VOTRE_CRON_SECRET
 */

if (PHP_SAPI !== 'cli') {
	$token = $_GET['token'] ?? '';
	if (!defined('CRON_SECRET') || $token !== CRON_SECRET) {
		http_response_code(403);
		exit('Accès refusé');
	}
}

require_once __DIR__ . '/../model/config.php';

$start = microtime(true);
echo '[' . date('Y-m-d H:i:s') . '] Démarrage envoi soldes...' . PHP_EOL;

try {
	$stats = Booking::sendPendingBalances();

	echo '[' . date('Y-m-d H:i:s') . '] '
		. "Envoyés : {$stats['sent']} | "
		. "Ignorés : {$stats['skipped']} | "
		. "Erreurs : " . count($stats['errors'])
		. PHP_EOL;

	foreach ($stats['errors'] as $err) {
		echo '[ERREUR] ' . $err . PHP_EOL;
	}

} catch (Exception $e) {
	echo '[ERREUR FATALE] ' . $e->getMessage() . PHP_EOL;
	exit(1);
}

$duration = round(microtime(true) - $start, 2);
echo '[' . date('Y-m-d H:i:s') . '] Terminé en ' . $duration . 's' . PHP_EOL;
