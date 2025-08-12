<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/parcours.php';

if (!isset($_GET['id'])) {
    header('Location: list_parcours.php');
    exit();
}

$id = intval($_GET['id']);
$error = '';
$success = false;

// Récupère les infos du parcours
$stmt = $pdo->prepare("SELECT nom_parcours, description_parcours FROM parcours WHERE id_parcours = ?");
$stmt->execute([$id]);
$parcours = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$parcours) {
    header('Location: list_parcours.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_parcours = $_POST['nom_parcours'];
    $description = $_POST['description'];

    if (!empty($nom_parcours) && !empty($description)) {
        $stmt = $pdo->prepare("UPDATE parcours SET nom_parcours = ?, description_parcours = ? WHERE id_parcours = ?");
        if ($stmt->execute([$nom_parcours, $description, $id])) {
            $success = true;
            // Recharge les infos modifiées
            $parcours['nom_parcours'] = $nom_parcours;
            $parcours['description_parcours'] = $description;
        } else {
            $error = "Erreur lors de la modification du parcours.";
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
    <title>Modifier un Parcours</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <h1>Modifier le Parcours</h1>
    <?php if ($success): ?>
        <div class="success">Parcours modifié avec succès.</div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <label for="nom_parcours">Nom du Parcours:</label>
        <input type="text" id="nom_parcours" name="nom_parcours" value="<?= htmlspecialchars($parcours['nom_parcours']) ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($parcours['description_parcours']) ?></textarea>

        <button type="submit">Enregistrer</button>
    </form>
    <a href="list_parcours.php">Retour à la liste des parcours</a>
</body>
</html>