<?php
function getPersonnagesCount($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM personnages");
    return $stmt->fetchColumn();
}

$etapesCount = getEtapesCount($pdo);
$chapitresCount = getChapitresCount($pdo);
$personnagesCount = getPersonnagesCount($pdo);
