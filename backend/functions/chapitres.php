<?php
function getChapitresCount($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM chapitres");
    return $stmt->fetchColumn();
}
function get_chapitres_by_etape($pdo, $id_etape)
{
    $stmt = $pdo->prepare("SELECT * FROM chapitres WHERE id_etape = ? ORDER BY ordre_chapitre");
    $stmt->execute([$id_etape]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function add_chapitre($pdo, $id_etape, $titre, $texte, $ordre, $image)
{
    $stmt = $pdo->prepare("INSERT INTO chapitres (id_etape, titre_chapitre, texte_chapitre, ordre_chapitre, image_chapitre) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$id_etape, $titre, $texte, $ordre, $image]);
}
// Récupère un chapitre par son id
function get_chapitre($pdo, $id_chapitre) {
    $stmt = $pdo->prepare("SELECT * FROM chapitres WHERE id_chapitre = ?");
    $stmt->execute([$id_chapitre]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Met à jour un chapitre
function update_chapitre($pdo, $id_chapitre, $titre, $texte, $ordre, $image) {
    $stmt = $pdo->prepare("UPDATE chapitres SET titre_chapitre = ?, texte_chapitre = ?, ordre_chapitre = ?, image_chapitre = ? WHERE id_chapitre = ?");
    return $stmt->execute([$titre, $texte, $ordre, $image, $id_chapitre]);
}
// Supprime un chapitre par son id
function delete_chapitre($pdo, $id_chapitre) {
    $stmt = $pdo->prepare("DELETE FROM chapitres WHERE id_chapitre = ?");
    return $stmt->execute([$id_chapitre]);
}
