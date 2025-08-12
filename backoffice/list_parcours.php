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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <title>Liste des Parcours</title>
</head>

<body>
    <h1>Liste des Parcours</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
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
                        <a href="edit_parcours.php?id=<?php echo $p['id']; ?>">Modifier</a>
                        <a href="delete_parcours.php?id=<?php echo $p['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce parcours ?');">Supprimer</a>
                        <a href="list_etapes.php?id_parcours=<?php echo $p['id']; ?>">Voir les étapes</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="add_parcours.php">Ajouter un nouveau parcours</a>
</body>

</html>