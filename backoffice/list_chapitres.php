<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/chapitres.php';

$id_etape = isset($_GET['id_etape']) ? intval($_GET['id_etape']) : 0;
// Récupérer l'id_parcours pour le lien retour
$stmt = $pdo->prepare("SELECT id_parcours FROM etapes WHERE id_etape = ?");
$stmt->execute([$id_etape]);
$id_parcours = $stmt->fetchColumn();
$titre_etape = $stmt->fetchColumn();

$chapitres = get_chapitres_by_etape($pdo, $id_etape);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style_backoffice.css">
    <link rel="stylesheet" href="css/alertes.css">
    <link rel="stylesheet" href="css/header_footer.css">
    <link rel="stylesheet" href="css/cards.css">
    <script src="js/admin.js" defer></script>
    <title>Liste des chapitres</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <?php
    // Récupérer le titre de l'étape
    $stmt = $pdo->prepare("SELECT id_parcours, titre_etape FROM etapes WHERE id_etape = ?");
    $stmt->execute([$id_etape]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $id_parcours = $row ? $row['id_parcours'] : null;
    $titre_etape = $row ? $row['titre_etape'] : '';
    ?>
    <h1 class="h1-sticky">Chapitres de l'étape "<?= htmlspecialchars($titre_etape) ?>"</h1>

    <main>
        <div class="cards-container">
            <div class="cards-grid">

                <div class="card-button">
                    <a href="add_chapitre.php?id_etape=<?= $id_etape ?>" class="button" style="margin-bottom:16px;">+ Ajouter un chapitre</a>
                    <a href="list_etapes.php?id_parcours=<?= $id_parcours ?>" class="button-bis">🔙 Retour à l'étape</a>
                </div>

                <?php foreach ($chapitres as $c): ?>
                    <div class="card">
                        <h2><?= htmlspecialchars($c['titre_chapitre']) ?></h2>

                        <div class="card-actions">
                            <a href="edit_chapitre.php?id=<?= $c['id_chapitre'] ?>" class="button-tab">Modifier</a>
                            <a href="delete_chapitre.php?id=<?= $c['id_chapitre'] ?>&id_etape=<?= $id_etape ?>" class="button-tab delete-parcours" onclick="return confirm('Supprimer ce chapitre ?');">Supprimer</a>
                        </div>

                        <h3>Numéro du chapitre</h3>
                        <p><?= $c['ordre_chapitre'] ?></p>

                        <h3>Illustration du chapitre</h3>
                        <div class="card-img">
                            <?php if (!empty($c['image_chapitre'])): ?>
                                <img src="/data/images/<?= htmlspecialchars($c['image_chapitre']) ?>" alt="Image chapitre" />
                            <?php else: ?>
                                <em>Non renseignée</em>
                            <?php endif; ?>
                        </div>
                        <h3>Texte</h3>
                        <p>
                            <?= htmlspecialchars($c['texte_chapitre']) ?>
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