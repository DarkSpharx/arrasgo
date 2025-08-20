<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/personnages.php';

$id_personnage = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_personnage) {
    delete_personnage($pdo, $id_personnage);
}
header('Location: list_personnages.php');
exit();
