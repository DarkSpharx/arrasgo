<?php
// Récupère tous les personnages
function get_all_personnages($pdo)
{
    $stmt = $pdo->query("SELECT * FROM personnages ORDER BY id_personnage DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ajoute un personnage
function add_personnage($pdo, $nom, $description, $image)
{
    $stmt = $pdo->prepare("INSERT INTO personnages (nom_personnage, description_personnage, image_personnage) VALUES (?, ?, ?)");
    return $stmt->execute([$nom, $description, $image]);
}

// Récupère un personnage par son id
function get_personnage($pdo, $id_personnage)
{
    $stmt = $pdo->prepare("SELECT * FROM personnages WHERE id_personnage = ?");
    $stmt->execute([$id_personnage]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Met à jour un personnage
function update_personnage($pdo, $id_personnage, $nom, $description, $image)
{
    $stmt = $pdo->prepare("UPDATE personnages SET nom_personnage = ?, description_personnage = ?, image_personnage = ? WHERE id_personnage = ?");
    return $stmt->execute([$nom, $description, $image, $id_personnage]);
}

// Supprime un personnage
function delete_personnage($pdo, $id_personnage)
{
    $stmt = $pdo->prepare("DELETE FROM personnages WHERE id_personnage = ?");
    return $stmt->execute([$id_personnage]);
}

// Compte le nombre de personnages
function getPersonnagesCount($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM personnages");
    return $stmt->fetchColumn();
}
