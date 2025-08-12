<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/parcours.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM parcours WHERE id_parcours = ?");
    $stmt->execute([$id]);
}

header('Location: list_parcours.php?deleted=1');
exit();
