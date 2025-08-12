<?php
function get_etapes_by_parcours($pdo, $id_parcours)
{
    $stmt = $pdo->prepare("SELECT * FROM etapes WHERE id_parcours = ?");
    $stmt->execute([$id_parcours]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_etape($pdo, $id_etape)
{
    $stmt = $pdo->prepare("SELECT * FROM etapes WHERE id_etape = ?");
    $stmt->execute([$id_etape]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function add_etape($pdo, $id_parcours, $titre, $mp3, $indice_texte, $indice_image, $question, $reponse, $lat, $lng, $ordre, $type_validation)
{
    $stmt = $pdo->prepare("INSERT INTO etapes (id_parcours, titre_etape, mp3_etape, indice_etape_texte, indice_etape_image, question_etape, reponse_attendue, latitude, longitude, ordre_etape, type_validation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([
        $id_parcours,
        $titre,
        $mp3,
        $indice_texte,
        $indice_image,
        $question,
        $reponse,
        $lat === '' ? null : $lat,
        $lng === '' ? null : $lng,
        $ordre,
        $type_validation
    ]);
}

function update_etape($pdo, $id_etape, $titre, $mp3, $indice_texte, $indice_image, $question, $reponse, $lat, $lng, $ordre, $type_validation)
{
    $stmt = $pdo->prepare("UPDATE etapes SET titre_etape=?, mp3_etape=?, indice_etape_texte=?, indice_etape_image=?, question_etape=?, reponse_attendue=?, latitude=?, longitude=?, ordre_etape=?, type_validation=? WHERE id_etape=?");
    return $stmt->execute([$titre, $mp3, $indice_texte, $indice_image, $question, $reponse, $lat, $lng, $ordre, $type_validation, $id_etape]);
}

function delete_etape($pdo, $id_etape)
{
    $stmt = $pdo->prepare("DELETE FROM etapes WHERE id_etape = ?");
    return $stmt->execute([$id_etape]);
}

function getEtapesCount($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM etapes");
    return $stmt->fetchColumn();
}
