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
    <link rel="stylesheet" href="css/alertes.css">
    <script src="js/admin.js" defer></script>
    <title>Liste des étapes</title>
    <link rel="icon" type="image/png" href="assets/favicon/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="assets/favicon/favicon.svg">
    <link rel="shortcut icon" href="assets/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-title" content="Arras Go Backoffice">
    <link rel="manifest" href="assets/favicon/site.webmanifest">
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
    <h1 class="h1-sticky">Étapes du parcours "<?= $nom_parcours ? htmlspecialchars($nom_parcours) : '' ?>"</h1>

    <main>
        <div class="cards-container">
            <div class="cards-grid">
                <div class="card-button">
                    <a href="add_etape.php?id_parcours=<?= $id_parcours; ?>" class="button">+ Ajouter une étape</a>
                    <a href="list_parcours.php" class="button-bis">🔙 Retour au Parcours</a>
                </div>

                <?php foreach ($etapes as $e): ?>
                    <div class="card">
                        <h2><?= htmlspecialchars($e['titre_etape']) ?></h2>

                        <div class="card-actions">
                            <a href="edit_etape.php?id=<?= $e['id_etape'] ?>" class="button-tab">Modifier</a>
                            <a href="delete_etape.php?id=<?= $e['id_etape'] ?>&id_parcours=<?= $id_parcours ?>" class="button-tab delete-parcours" onclick="return confirm('Supprimer cette étape ?');">Supprimer</a>
                            <a href="list_chapitres.php?id_etape=<?= $e['id_etape'] ?>" class="button-tab">Voir les chapitres</a>
                        </div>

                        <h3>Numéro d'étape</h3>
                        <p><?= $e['ordre_etape'] ?></p>

                        <h3>Illustration de l'étape</h3>
                        <div class="card-img">
                            <?php if (!empty($e['image_header'])): ?>
                                <img src="/data/images/<?= htmlspecialchars($e['image_header']) ?>" alt="Image header" />
                            <?php else: ?>
                                <em>Non renseignée</em>
                            <?php endif; ?>
                        </div>

                        <h3>Retranscription audio</h3>
                        <?php if (!empty($e['mp3_etape'])): ?>
                            <audio src="/data/mp3/<?= htmlspecialchars($e['mp3_etape']) ?>" controls class="card-audio"></audio>
                        <?php endif; ?>

                        <h3>Illustration pour la page question</h3>
                        <div class="card-img">
                            <?php if (!empty($e['image_question'])): ?>
                                <img src="/data/images/<?= htmlspecialchars($e['image_question']) ?>" alt="Image question" />
                            <?php else: ?>
                                <em>Non renseignée</em>
                            <?php endif; ?>
                        </div>

                        <h3>Indice textuel</h3>
                        <p>
                            <?= !empty($e['indice_etape_texte']) ? htmlspecialchars($e['indice_etape_texte']) : '<em>Non renseigné</em>' ?>
                        </p>

                        <h3>Indice visuel</h3><br>
                        <div class="card-img">
                            <?php if (!empty($e['indice_etape_image'])): ?>
                                <img src="/data/images/<?= htmlspecialchars($e['indice_etape_image']) ?>" alt="Indice image" />
                            <?php else: ?>
                                <em>Non renseigné</em>
                            <?php endif; ?>
                        </div>

                        <h3>Question</h3>
                        <p>
                            <?= !empty($e['question_etape']) ? htmlspecialchars($e['question_etape']) : '<em>Non renseignée</em>' ?>
                        </p>

                        <h3>Réponse attendue</h3>
                        <p>
                            <?= !empty($e['reponse_attendue']) ? htmlspecialchars($e['reponse_attendue']) : '<em>Non renseignée</em>' ?>
                        </p>

                        <h3>Latitude :</h3>
                        <p>
                            <?= $e['latitude'] !== null && $e['latitude'] !== '' ? htmlspecialchars($e['latitude']) : '<em>Non renseignée</em>' ?>
                        </p>

                        <h3>Longitude</h3>
                        <p>
                            <?= $e['longitude'] !== null && $e['longitude'] !== '' ? htmlspecialchars($e['longitude']) : '<em>Non renseignée</em>' ?>
                        </p>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
</body>

</html>