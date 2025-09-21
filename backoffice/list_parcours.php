<?php
// Vérifie si l'utilisateur est connecté
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/functions/parcours.php';
require_once '../backend/config/database.php'; // ajoute cette ligne si elle manque

// Récupère la liste des parcours (en ligne et brouillon)
$parcours = get_all_parcours_backoffice($pdo);

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
    <title>Liste des Parcours</title>
    <link rel="icon" type="image/png" href="assets/favicon/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="assets/favicon/favicon.svg">
    <link rel="shortcut icon" href="assets/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-title" content="Arras Go Backoffice">
    <link rel="manifest" href="assets/favicon/site.webmanifest">
</head>

<body>
    <?php include 'header.php'; ?>

    <h1 class="h1-sticky">ARRAS GO - Parcours</h1>

    <main>
        <div class="cards-container">
            <div class="cards-grid">
                <div class="card-button">
                    <a href="add_parcours.php" class="button">+ Ajouter un parcours</a>
                    <a href="dashboard.php" class="button-bis">🔙 Retour au Dashboard</a>
                </div>

                <?php foreach ($parcours as $p): ?>
                    <div class="card">

                        <h2><?= htmlspecialchars($p['nom']); ?></h2>

                        <div class="card-actions">
                            <a class="button-tab" href="edit_parcours.php?id=<?= $p['id']; ?>">Modifier</a>
                            <a class="button-tab" href="delete_parcours.php?id=<?= $p['id']; ?>" onclick="return confirm('Êtes-vous sûr ?');">Supprimer</a>
                            <a class="button-tab" href="list_etapes.php?id_parcours=<?= $p['id']; ?>">Voir les étapes</a>
                        </div>

                        <h3>Statut</h3>
                        <p>
                            <?php if (isset($p['statut']) && $p['statut'] == 1): ?>
                                <span style="color:green;font-weight:bold;">En ligne</span>
                            <?php else: ?>
                                <span style="color:red;font-weight:bold;">Brouillon</span>
                            <?php endif; ?>
                        </p>

                        <h3>Illustration du parcours</h3>
                        <div class="card-img">
                            <?php if (!empty($p['image_parcours'])): ?>
                                <img src="/data/images/<?= htmlspecialchars($p['image_parcours']) ?>" alt="Image parcours">
                            <?php endif; ?>
                        </div>

                        <h3>Description du parcours</h3>
                        <p><?= htmlspecialchars($p['description']); ?></p>

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