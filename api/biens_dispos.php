<?php
/**
 * API - Disponibilité de tous les biens sur une période donnée
 * GET /api/biens_dispos.php?date_from=YYYY-MM-DD&date_to=YYYY-MM-DD
 *
 * Retourne la liste des IDs Smoobu des biens disponibles
 */
require_once '../model/config.php';

header('Content-Type: application/json');

// Rate limiting
use Osimatic\Security\RateLimiter;
$clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (!(new RateLimiter())->check('biens_dispos_' . $clientIp, 10, 60)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'error' => 'Trop de requêtes, veuillez réessayer plus tard']);
    exit;
}

$dateFrom = trim($_GET['date_from'] ?? '');
$dateTo   = trim($_GET['date_to']   ?? '');

// Validation dates
if (!$dateFrom || !$dateTo) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Dates manquantes']);
    exit;
}
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Format de date invalide (YYYY-MM-DD)']);
    exit;
}
$dFrom = new DateTime($dateFrom);
$dTo   = new DateTime($dateTo);
if ($dFrom >= $dTo) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'La date de départ doit être après la date d\'arrivée']);
    exit;
}
if ($dFrom < new DateTime('today')) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'La date d\'arrivée ne peut pas être dans le passé']);
    exit;
}

// Récupérer tous les biens en location ayant un ID Smoobu
$bienModel = new Bien();
$biens = $bienModel->getAll('location', true);
$biensAvecSmoobu = array_filter($biens, fn($b) => !empty($b['id_smoobu']));

if (empty($biensAvecSmoobu)) {
    echo json_encode(['success' => true, 'disponibles' => []]);
    exit;
}

// Vérifier la dispo de chaque bien (séquentiellement — Smoobu n'a pas d'endpoint batch)
$disponibles = [];
$erreurs = 0;

foreach ($biensAvecSmoobu as $bien) {
    $apartmentId = (int) $bien['id_smoobu'];
    try {
        $pricing = Booking::computePrice($apartmentId, $dateFrom, $dateTo);
        if (!empty($pricing['available'])) {
            $disponibles[] = $apartmentId;
        }
    } catch (Exception $e) {
        // En cas d'erreur API pour un bien, on l'exclut prudemment
        $erreurs++;
    }
}

echo json_encode([
    'success'     => true,
    'disponibles' => $disponibles,
    'date_from'   => $dateFrom,
    'date_to'     => $dateTo,
    'total_verifies' => count($biensAvecSmoobu),
    'erreurs'     => $erreurs,
]);
