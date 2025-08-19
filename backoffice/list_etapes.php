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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style_backoffice.css">
    <link rel="stylesheet" href="css/header_footer.css">
    <link rel="stylesheet" href="css/cards.css">
    <link rel="stylesheet" href="css/tab.css">
    <script src="js/admin.js" defer></script>
    <title>Liste des étapes</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <h1 class="h1-sticky">Étapes du parcours</h1>
    <main>
        <div class="cards-container">
            <a href="add_etape.php?id_parcours=<?= $id_parcours; ?>" class="button" style="margin-bottom:16px;">Ajouter une étape</a>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Ordre</th>
                        <th>Image header</th>
                        <th>Image question</th>
                        <th>Indice texte</th>
                        <th>Indice image</th>
                        <th>Question</th>
                        <th>MP3</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($etapes as $e): ?>
                        <tr>
                            <td data-label="ID">#<?= $e['id_etape'] ?></td>
                            <td data-label="Titre"><?= htmlspecialchars($e['titre_etape']) ?></td>
                            <td data-label="Ordre"><?= $e['ordre_etape'] ?></td>
                            <td data-label="Image header">
                                <?php if (!empty($e['image_header'])): ?>
                                    <img src="/data/images/<?= htmlspecialchars($e['image_header']) ?>" alt="Image header" class="tab-img" />
                                <?php endif; ?>
                            </td>
                            <td data-label="Image question">
                                <?php if (!empty($e['image_question'])): ?>
                                    <img src="/data/images/<?= htmlspecialchars($e['image_question']) ?>" alt="Image question" class="tab-img" />
                                <?php endif; ?>
                            </td>
                            <td data-label="Indice texte"><?= htmlspecialchars($e['indice_etape_texte']) ?></td>
                            <td data-label="Indice image">
                                <?php if (!empty($e['indice_etape_image'])): ?>
                                    <img src="/data/images/<?= htmlspecialchars($e['indice_etape_image']) ?>" alt="Indice image" class="tab-indice-img" />
                                <?php endif; ?>
                            </td>
                            <td data-label="Question"><?= htmlspecialchars($e['question_etape']) ?></td>
                            <td data-label="MP3">
                                <?php if (!empty($e['mp3_etape'])): ?>
                                    <audio src="/data/mp3/<?= htmlspecialchars($e['mp3_etape']) ?>" controls class="tab-audio"></audio>
                                <?php endif; ?>
                            </td>
                            <td data-label="Actions">
                                <div class="tab-actions">
                                    <a href="edit_etape.php?id=<?= $e['id_etape'] ?>" class="button-tab">Modifier</a>
                                    <a href="delete_etape.php?id=<?= $e['id_etape'] ?>&id_parcours=<?= $id_parcours ?>" class="button-tab delete-parcours" onclick="return confirm('Supprimer cette étape ?');">Supprimer</a>
                                    <a href="list_chapitres.php?id_etape=<?= $e['id_etape'] ?>" class="button-tab">Voir les chapitres</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="list_parcours.php" class="button" style="margin-top:24px;">Retour aux parcours</a>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
</body>

</html>