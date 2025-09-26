<?php
// Inclusion du fichier de configuration pour la connexion à la base de données
require_once '../config/database.php';

// Fonction pour récupérer les parcours
function getParcours($pdo)
{
    // SELECT * conservé pour éviter toute rupture si les noms de colonnes varient
    $query = "SELECT * FROM parcours";
    $stmt  = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Vérification de la méthode de requête
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Si la requête est en GET, on renvoie une réponse JSON en UTF-8 au navigateur
    header('Content-Type: application/json; charset=utf-8');

    try {
        $parcours = getParcours($pdo); // lecture via PDO préparé
        echo json_encode($parcours, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la récupération des parcours.']);
        exit;
    }
} else {
    http_response_code(405);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Méthode non autorisée.']);
    exit;
}
