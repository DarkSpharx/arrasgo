<?php
session_start();
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../backend/functions/parcours.php';
require_once __DIR__ . '/../backend/security/check_auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_parcours = $_POST['nom_parcours'];
    $description = $_POST['description'];
    $id_user = $_SESSION['admin_id']; // récupère l'id de l'admin connecté

    if (!empty($nom_parcours) && !empty($description)) {
        $result = add_parcours($pdo, $id_user, $nom_parcours, $description);
        if ($result) {
            header('Location: list_parcours.php?success=1');
            exit();
        } else {
            $error = "Erreur lors de l'ajout du parcours.";
        }
    } else {
        $error = "Tous les champs doivent être remplis.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <title>Ajouter un Parcours</title>
</head>

<body>
    <h1>Ajouter un Nouveau Parcours</h1>
    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="add_parcours.php" method="POST">
        <label for="nom_parcours">Nom du Parcours:</label>
        <input type="text" id="nom_parcours" name="nom_parcours" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <button type="submit">Ajouter</button>
    </form>
    <a href="list_parcours.php">Retour à la liste des parcours</a>
</body>

</html>
<?php
// parcours.php

function add_parcours($pdo, $id_user, $nom_parcours, $description) {
    $stmt = $pdo->prepare("INSERT INTO parcours (id_user, nom_parcours, description_parcours) VALUES (?, ?, ?)");
    return $stmt->execute([$id_user, $nom_parcours, $description]);
}
