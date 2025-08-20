<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/chapitres.php';

$id_chapitre = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_etape = isset($_GET['id_etape']) ? intval($_GET['id_etape']) : 0;
if ($id_chapitre) {
    delete_chapitre($pdo, $id_chapitre);
}
header('Location: list_chapitres.php?id_etape=' . $id_etape);
exit();
