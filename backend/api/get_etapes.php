<?php
require_once '../config/database.php';
require_once '../functions/etapes.php';

// lecture du paramètre GET → réponse JSON (UTF-8)
header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['id_parcours'])) {
    $id = (int) $_GET['id_parcours'];
    $etapes = get_etapes_by_parcours($pdo, $id);
    echo json_encode($etapes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
} else {
    http_response_code(400);
    echo json_encode(['error' => 'paramètre id_parcours manquant']);
    exit;
}
