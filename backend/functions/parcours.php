<?php
// Fonction pour créer un nouveau parcours
function createParcours($pdo, $name, $description) {
    $stmt = $pdo->prepare("INSERT INTO parcours (name, description) VALUES (:name, :description)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    return $stmt->execute();
}

// Fonction pour lire tous les parcours
function readParcours($pdo) {
    $stmt = $pdo->query("SELECT * FROM parcours");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour mettre à jour un parcours
function updateParcours($pdo, $id, $name, $description) {
    $stmt = $pdo->prepare("UPDATE parcours SET name = :name, description = :description WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    return $stmt->execute();
}

// Fonction pour supprimer un parcours
function deleteParcours($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM parcours WHERE id = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
?>