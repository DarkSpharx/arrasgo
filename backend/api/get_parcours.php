<?php
// Inclusion du fichier de configuration pour la connexion à la base de données
require_once '../config/database.php';

// Fonction pour récupérer les parcours
function getParcours($pdo) {
    $query = "SELECT * FROM parcours"; // Remplacez 'parcours' par le nom de votre table
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Vérification de la méthode de requête
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $parcours = getParcours($pdo);
        header('Content-Type: application/json');
        echo json_encode($parcours);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la récupération des parcours.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée.']);
}
?>