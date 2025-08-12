<?php
require_once '../config/database.php';
require_once '../functions/etapes.php';

if (isset($_GET['id_parcours'])) {
    $etapes = get_etapes_by_parcours($pdo, intval($_GET['id_parcours']));
    header('Content-Type: application/json');
    echo json_encode($etapes);
}
