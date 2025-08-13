<?php
// Fonction pour créer un nouveau parcours
function createParcours($pdo, $name, $description)
{
    $stmt = $pdo->prepare("INSERT INTO parcours (name, description) VALUES (:name, :description)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    return $stmt->execute();
}

// Fonction pour lire tous les parcours
function readParcours($pdo)
{
    $stmt = $pdo->query("SELECT * FROM parcours");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour mettre à jour un parcours
function updateParcours($pdo, $id, $name, $description)
{
    $stmt = $pdo->prepare("UPDATE parcours SET name = :name, description = :description WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    return $stmt->execute();
}

// Fonction pour supprimer un parcours
function deleteParcours($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM parcours WHERE id = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function getParcoursCount($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM parcours");
    return $stmt->fetchColumn();
}

function getUsersCount($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM users_admins");
    return $stmt->fetchColumn();
}

function add_parcours($pdo, $id_user, $nom_parcours, $description, $image_parcours)
{
    $stmt = $pdo->prepare("INSERT INTO parcours (id_user, nom_parcours, description_parcours, image_parcours) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$id_user, $nom_parcours, $description, $image_parcours]);
}

function update_parcours($pdo, $id_parcours, $nom_parcours, $description, $image_parcours)
{
    $stmt = $pdo->prepare("UPDATE parcours SET nom_parcours = ?, description_parcours = ?, image_parcours = ? WHERE id_parcours = ?");
    return $stmt->execute([$nom_parcours, $description, $image_parcours, $id_parcours]);
}

function get_all_parcours($pdo)
{
    $stmt = $pdo->query("SELECT id_parcours AS id, nom_parcours AS nom, description_parcours AS description, image_parcours FROM parcours");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_parcours($pdo, $id_parcours)
{
    $stmt = $pdo->prepare("SELECT * FROM parcours WHERE id_parcours = ?");
    $stmt->execute([$id_parcours]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
