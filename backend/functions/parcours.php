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

function getParcoursCount($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM parcours");
    return $stmt->fetchColumn();
}

function getUsersCount($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM users_admins");
    return $stmt->fetchColumn();
}

function get_all_parcours($pdo) {
    $stmt = $pdo->query("SELECT id_parcours AS id, nom_parcours AS nom, description_parcours AS description FROM parcours");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>