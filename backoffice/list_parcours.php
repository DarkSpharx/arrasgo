<?php
// Vérifie si l'utilisateur est connecté
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/functions/parcours.php';
require_once '../backend/config/database.php'; // ajoute cette ligne si elle manque

// Récupère la liste des parcours
$parcours = get_all_parcours($pdo);

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
    <title>Liste des Parcours</title>
</head>

<body>
    <?php include 'header.php'; ?>

    <h1 class="h1-sticky">ARRAS GO - Les Parcours</h1>

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