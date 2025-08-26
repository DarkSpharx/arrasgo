<?php
// Fonction pour créer un nouveau parcours (statut brouillon par défaut)
function createParcours($pdo, $name, $description, $image = '', $id_user = null, $statut = 0)
{
    $stmt = $pdo->prepare("INSERT INTO parcours (nom_parcours, description_parcours, image_parcours, id_user, statut) VALUES (:name, :description, :image, :id_user, :statut)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':id_user', $id_user);
    $stmt->bindParam(':statut', $statut, PDO::PARAM_INT);
    return $stmt->execute();
}

// Fonction pour lire tous les parcours
function readParcours($pdo)
{
    $stmt = $pdo->query("SELECT * FROM parcours");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour mettre à jour un parcours (y compris le statut)
function updateParcours($pdo, $id, $name, $description, $image = '', $statut = 0)
{
    $stmt = $pdo->prepare("UPDATE parcours SET nom_parcours = :name, description_parcours = :description, image_parcours = :image, statut = :statut WHERE id_parcours = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':statut', $statut, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id);
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

// Ajoute un parcours (statut brouillon par défaut)
function add_parcours($pdo, $id_user, $nom_parcours, $description, $image_parcours, $statut = 0)
{
    $stmt = $pdo->prepare("INSERT INTO parcours (id_user, nom_parcours, description_parcours, image_parcours, statut) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$id_user, $nom_parcours, $description, $image_parcours, $statut]);
}

// Met à jour un parcours (y compris le statut)
function update_parcours($pdo, $id_parcours, $nom_parcours, $description, $image_parcours, $statut)
{
    $stmt = $pdo->prepare("UPDATE parcours SET nom_parcours = ?, description_parcours = ?, image_parcours = ?, statut = ? WHERE id_parcours = ?");
    return $stmt->execute([$nom_parcours, $description, $image_parcours, $statut, $id_parcours]);
}

function get_all_parcours($pdo)
{
    $stmt = $pdo->query("SELECT id_parcours AS id, nom_parcours AS nom, description_parcours AS description, image_parcours, statut FROM parcours");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_parcours($pdo, $id_parcours)
{
    $stmt = $pdo->prepare("SELECT * FROM parcours WHERE id_parcours = ?");
    $stmt->execute([$id_parcours]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
