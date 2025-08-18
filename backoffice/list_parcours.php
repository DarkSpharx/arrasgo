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
    <h1 class="h1-sticky">Parcours</h1>
    <main>
        <section class="container">
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
                            <td><?php echo htmlspecialchars($p['id']); ?></td>
                            <td><?php echo htmlspecialchars($p['nom']); ?></td>
                            <td><?php echo htmlspecialchars($p['description']); ?></td>
                            <td>
                                <?php if (!empty($p['image_parcours'])): ?>
                                    <img src="/data/images/<?= htmlspecialchars($p['image_parcours']) ?>" alt="Image parcours" style="max-width:80px;max-height:80px;">
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_parcours.php?id=<?php echo $p['id']; ?>">Modifier</a>
                                <a href="delete_parcours.php?id=<?php echo $p['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce parcours ?');">Supprimer</a>
                                <a href="list_etapes.php?id_parcours=<?php echo $p['id']; ?>">Voir les étapes</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="container-horizontal">
            <a href="add_parcours.php" class="button">Ajouter un nouveau parcours</a>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
</body>

</html>