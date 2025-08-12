<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/etapes.php';

$id_parcours = isset($_GET['id_parcours']) ? intval($_GET['id_parcours']) : 0;
$etapes = get_etapes_by_parcours($pdo, $id_parcours);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des étapes</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <h1>Étapes du parcours</h1>
    <a href="add_etape.php?id_parcours=<?php echo $id_parcours; ?>">Ajouter une étape</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>MP3</th>
                <th>Indice texte</th>
                <th>Indice image</th>
                <th>Question</th>
                <th>Réponse attendue</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Ordre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($etapes as $e): ?>
                <tr>
                    <td><?= $e['id_etape'] ?></td>
                    <td><?= htmlspecialchars($e['titre_etape']) ?></td>
                    <td>
                        <?php if (!empty($e['mp3_etape'])): ?>
                            <audio src="/data/mp3/<?= htmlspecialchars($e['mp3_etape']) ?>" controls></audio>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($e['indice_etape_texte']) ?></td>
                    <td>
                        <?php if (!empty($e['indice_etape_image'])): ?>
                            <img src="/data/images/<?= htmlspecialchars($e['indice_etape_image']) ?>" alt="Indice image" style="max-width:80px;max-height:80px;">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($e['question_etape']) ?></td>
                    <td><?= htmlspecialchars($e['reponse_attendue']) ?></td>
                    <td><?= htmlspecialchars($e['latitude'] ?? '') ?></td>
                    <td><?= htmlspecialchars($e['longitude'] ?? '') ?></td>
                    <td><?= $e['ordre_etape'] ?></td>
                    <td>
                        <a href="edit_etape.php?id=<?php echo $e['id_etape']; ?>">Modifier</a>
                        <a href="delete_etape.php?id=<?php echo $e['id_etape']; ?>&id_parcours=<?php echo $id_parcours; ?>" onclick="return confirm('Supprimer cette étape ?');">Supprimer</a>
                        <a href="list_chapitres.php?id_etape=<?php echo $e['id_etape']; ?>" class="button">Voir les chapitres</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="list_parcours.php">Retour aux parcours</a>
</body>

</html>