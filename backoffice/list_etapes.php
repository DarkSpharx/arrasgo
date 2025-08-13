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
    <link rel="stylesheet" href="css/style_backoffice.css">
    <script src="js/admin.js" defer></script>
</head>

<body>
    <?php include 'header.php'; ?>
    <h1>Étapes du parcours</h1>
    <a href="add_etape.php?id_parcours=<?php echo $id_parcours; ?>">Ajouter une étape</a>
    <div class="etapes-cards">
        <?php foreach ($etapes as $e): ?>
            <div class="etape-card">
                <div class="etape-card-header">
                    <strong>#<?= $e['id_etape'] ?> - <?= htmlspecialchars($e['titre_etape']) ?></strong>
                    <span>Ordre : <?= $e['ordre_etape'] ?></span>
                </div>
                <?php if (!empty($e['image_header'])): ?>
                    <div class="etape-card-img">
                        <img src="/data/images/<?= htmlspecialchars($e['image_header']) ?>" alt="Illustration étape">
                    </div>
                <?php endif; ?>
                <?php if (!empty($e['image_question'])): ?>
                    <div class="etape-card-img">
                        <img src="/data/images/<?= htmlspecialchars($e['image_question']) ?>" alt="Illustration question">
                    </div>
                <?php endif; ?>
                <div class="etape-card-body">
                    <?php if (!empty($e['mp3_etape'])): ?>
                        <div><audio src="/data/mp3/<?= htmlspecialchars($e['mp3_etape']) ?>" controls></audio></div>
                    <?php endif; ?>
                    <div><strong>Indice texte :</strong> <?= htmlspecialchars($e['indice_etape_texte']) ?></div>
                    <?php if (!empty($e['indice_etape_image'])): ?>
                        <div>
                            <img src="/data/images/<?= htmlspecialchars($e['indice_etape_image']) ?>" alt="Indice image" style="max-width:80px;max-height:80px;">
                        </div>
                    <?php endif; ?>
                    <div><strong>Question :</strong> <?= htmlspecialchars($e['question_etape']) ?></div>
                    <div><strong>Réponse attendue :</strong> <?= htmlspecialchars($e['reponse_attendue']) ?></div>
                    <div><strong>Latitude :</strong> <?= htmlspecialchars($e['latitude'] ?? '') ?> | <strong>Longitude :</strong> <?= htmlspecialchars($e['longitude'] ?? '') ?></div>
                </div>
                <div class="etape-card-actions">
                    <a href="edit_etape.php?id=<?= $e['id_etape']; ?>">Modifier</a>
                    <a href="delete_etape.php?id=<?= $e['id_etape']; ?>&id_parcours=<?= $id_parcours; ?>" onclick="return confirm('Supprimer cette étape ?');">Supprimer</a>
                    <a href="list_chapitres.php?id_etape=<?= $e['id_etape']; ?>" class="button">Voir les chapitres</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="list_parcours.php">Retour aux parcours</a>
</body>

</html>