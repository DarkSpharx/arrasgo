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
    <h1 class="h1-sticky">Parcours de ARRAS GO</h1>
    <main>
        <div class="cards-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($parcours as $p): ?>
                        <tr>
                            <td data-label="ID"><?= htmlspecialchars($p['id']); ?></td>
                            <td data-label="Nom"><?= htmlspecialchars($p['nom']); ?></td>
                            <td data-label="Description"><?= htmlspecialchars($p['description']); ?></td>
                            <td data-label="Image">
                                <?php if (!empty($p['image_parcours'])): ?>
                                    <img src="/data/images/<?= htmlspecialchars($p['image_parcours']) ?>" alt="Image parcours">
                                <?php endif; ?>
                            </td>
                            <td data-label="Actions">
                                <div class="tab-actions">
                                    <a class="button-tab" href="edit_parcours.php?id=<?= $p['id']; ?>">Modifier</a>
                                    <a class="button-tab" href="delete_parcours.php?id=<?= $p['id']; ?>" onclick="return confirm('Êtes-vous sûr ?');">Supprimer</a>
                                    <a class="button-tab" href="list_etapes.php?id_parcours=<?= $p['id']; ?>">Voir les étapes</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="container">
            <a href="add_parcours.php" class="button">Ajouter un parcours</a>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
</body>

</html>