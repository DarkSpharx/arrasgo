<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/etapes.php';

$id_etape = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_parcours = isset($_GET['id_parcours']) ? intval($_GET['id_parcours']) : 0;
if ($id_etape) {
    delete_etape($pdo, $id_etape);
}
header('Location: list_etapes.php?id_parcours=' . $id_parcours);
exit();
