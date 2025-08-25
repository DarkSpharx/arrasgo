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
    <link rel="stylesheet" href="css/tab.css">
    <script src="js/admin.js" defer></script>
    <title>Liste des étapes</title>
</head>

<body>
    <?php include 'header.php'; ?>


    <?php
    // Récupérer le nom du parcours pour l'affichage du titre
    $nom_parcours = '';
    if ($id_parcours) {
        $stmt = $pdo->prepare('SELECT nom_parcours FROM parcours WHERE id_parcours = ?');
        $stmt->execute([$id_parcours]);
        $nom_parcours = $stmt->fetchColumn();
    }
    ?>
    <h1 class="h1-sticky">Étapes du parcours : "<?= $nom_parcours ? htmlspecialchars($nom_parcours) : '' ?>"</h1>

    <main>
        <div class="cards-container">
            <div class="cards-grid">
                <div class="card-button">
                    <a href="add_etape.php?id_parcours=<?= $id_parcours; ?>" class="button" style="margin-bottom:16px;">Ajouter une étape</a>
                    <a href="list_parcours.php" class="button-tab">🔙 Retour au Parcours</a>
                </div>
                <?php foreach ($etapes as $e): ?>
                    <div class="card">

                        <h2>#<?= $e['id_etape'] ?> - <?= htmlspecialchars($e['titre_etape']) ?></h2>

                        <h3>Numéro d'ordre de l'étape</h3>
                        <p><?= $e['ordre_etape'] ?></p>


                        <h3>Illustration de l'étape</h3>
                        <div class="card-imgs">
                            <?php if (!empty($e['image_header'])): ?>
                                <img src="/data/images/<?= htmlspecialchars($e['image_header']) ?>" alt="Image header" />
                            <?php else: ?>
                                <em>Non renseignée</em>
                            <?php endif; ?>
                        </div>

                        <h3>Illustration pour la page question</h3>
                        <div class="card-imgs">
                            <?php if (!empty($e['image_question'])): ?>
                                <img src="/data/images/<?= htmlspecialchars($e['image_question']) ?>" alt="Image question" />
                            <?php else: ?>
                                <em>Non renseignée</em>
                            <?php endif; ?>
                        </div>
                        <strong>Indice textuel :</strong> <?= !empty($e['indice_etape_texte']) ? htmlspecialchars($e['indice_etape_texte']) : '<em>Non renseigné</em>' ?>
                        <div>
                            <strong>Indice visuel :</strong><br>
                            <?php if (!empty($e['indice_etape_image'])): ?>
                                <img src="/data/images/<?= htmlspecialchars($e['indice_etape_image']) ?>" alt="Indice image" class="tab-indice-img" />
                            <?php else: ?>
                                <em>Non renseigné</em>
                            <?php endif; ?>
                        </div>
                        <strong>Question :</strong> <?= !empty($e['question_etape']) ? htmlspecialchars($e['question_etape']) : '<em>Non renseignée</em>' ?>
                        <div><strong>Réponse attendue :</strong> <?= !empty($e['reponse_attendue']) ? htmlspecialchars($e['reponse_attendue']) : '<em>Non renseignée</em>' ?></div>
                        <div><strong>Latitude :</strong> <?= $e['latitude'] !== null && $e['latitude'] !== '' ? htmlspecialchars($e['latitude']) : '<em>Non renseignée</em>' ?></div>
                        <div><strong>Longitude :</strong> <?= $e['longitude'] !== null && $e['longitude'] !== '' ? htmlspecialchars($e['longitude']) : '<em>Non renseignée</em>' ?></div>

                        <?php if (!empty($e['mp3_etape'])): ?>
                            <audio src="/data/mp3/<?= htmlspecialchars($e['mp3_etape']) ?>" controls class="tab-audio"></audio>
                        <?php endif; ?>
                        <div class="etape-card-actions">
                            <a href="edit_etape.php?id=<?= $e['id_etape'] ?>" class="button-tab">Modifier</a>
                            <a href="delete_etape.php?id=<?= $e['id_etape'] ?>&id_parcours=<?= $id_parcours ?>" class="button-tab delete-parcours" onclick="return confirm('Supprimer cette étape ?');">Supprimer</a>
                            <a href="list_chapitres.php?id_etape=<?= $e['id_etape'] ?>" class="button-tab">Voir les chapitres</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="list_parcours.php" class="button" style="margin-top:24px;">Retour aux parcours</a>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
</body>

</html>