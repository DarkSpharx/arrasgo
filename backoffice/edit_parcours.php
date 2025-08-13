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
$stmt = $pdo->prepare("SELECT nom_parcours, description_parcours, image_parcours FROM parcours WHERE id_parcours = ?");
$stmt->execute([$id]);
$parcours = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$parcours) {
    header('Location: list_parcours.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_parcours = $_POST['nom_parcours'];
    $description = $_POST['description'];

    // Gestion de l'image
    $image_parcours = $parcours['image_parcours'];
    if (isset($_FILES['image_parcours']) && $_FILES['image_parcours']['error'] == UPLOAD_ERR_OK) {
        $img_name = uniqid() . '_' . basename($_FILES['image_parcours']['name']);
        $img_path = __DIR__ . '/../data/images/' . $img_name;
        if (move_uploaded_file($_FILES['image_parcours']['tmp_name'], $img_path)) {
            $image_parcours = $img_name;
        }
    }

    if (!empty($nom_parcours) && !empty($description)) {
        $stmt = $pdo->prepare("UPDATE parcours SET nom_parcours = ?, description_parcours = ?, image_parcours = ? WHERE id_parcours = ?");
        if ($stmt->execute([$nom_parcours, $description, $image_parcours, $id])) {
            $success = true;
            // Recharge les infos modifiées
            $parcours['nom_parcours'] = $nom_parcours;
            $parcours['description_parcours'] = $description;
            $parcours['image_parcours'] = $image_parcours;
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
    <link rel="stylesheet" href="css/style_backoffice.css">
    <script src="js/admin.js" defer></script>
</head>

<body>
    <?php include 'header.php'; ?>
    <h1>Modifier le Parcours</h1>
    <?php if ($success): ?>
        <div class="success">Parcours modifié avec succès.</div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data" class="form-parcours">
        <label for="nom_parcours">Nom du Parcours:</label>
        <input type="text" id="nom_parcours" name="nom_parcours" value="<?= htmlspecialchars($parcours['nom_parcours']) ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($parcours['description_parcours']) ?></textarea>

        <div class="form-group">
            <label for="image_parcours">Image d'illustration du parcours :</label>
            <?php if (!empty($parcours['image_parcours'])): ?>
                <img src="/data/images/<?= htmlspecialchars($parcours['image_parcours']) ?>" alt="Image parcours" style="max-width:80px;max-height:80px;">
                <br>
                <small>Fichier actuel : <?= htmlspecialchars($parcours['image_parcours']) ?></small>
            <?php endif; ?>
            <input type="file" id="image_parcours" name="image_parcours" accept="image/*">
        </div>

        <button type="submit">Enregistrer</button>
    </form>
    <a href="list_parcours.php">Retour à la liste des parcours</a>
</body>

</html>