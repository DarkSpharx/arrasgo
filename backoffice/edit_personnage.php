<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/personnages.php';

$id_personnage = isset($_GET['id']) ? intval($_GET['id']) : 0;
$personnage = get_personnage($pdo, $id_personnage);
if (!$personnage) {
    header('Location: personnages.php');
    exit();
}
$error = '';
$success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom_personnage'];
    $description = $_POST['description_personnage'];
    $image = $personnage['image_personnage'];
    if (isset($_FILES['image_personnage']) && $_FILES['image_personnage']['error'] == UPLOAD_ERR_OK) {
        $img_name = uniqid() . '_' . basename($_FILES['image_personnage']['name']);
        $img_path = __DIR__ . '/../data/images/' . $img_name;
        if (move_uploaded_file($_FILES['image_personnage']['tmp_name'], $img_path)) {
            $image = $img_name;
        } else {
            $error = "Erreur lors de l'upload de l'image.";
        }
    }
    if (!empty($nom) && !empty($description)) {
        $result = update_personnage($pdo, $id_personnage, $nom, $description, $image);
        if ($result) {
            $success = true;
            $personnage = get_personnage($pdo, $id_personnage);
        } else {
            $error = "Erreur lors de la mise à jour du personnage.";
        }
    } else {
        $error = "Le nom et la description sont obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style_backoffice.css">
    <link rel="stylesheet" href="css/header_footer.css">
    <script src="js/admin.js" defer></script>
    <title>Modifier un personnage</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <h1 class="h1-sticky">Modifier le personnage</h1>
    <main>
        <div class="form-container">
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php elseif ($success): ?>
                <div class="success">Personnage mis à jour avec succès.</div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" class="formulaire">
                <div class="form-group">
                    <label for="nom_personnage">Nom :</label>
                    <input type="text" id="nom_personnage" name="nom_personnage" value="<?= htmlspecialchars($personnage['nom_personnage']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="description_personnage">Description :</label>
                    <textarea id="description_personnage" name="description_personnage" required><?= htmlspecialchars($personnage['description_personnage']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="image_personnage">Image :</label>
                    <input type="file" id="image_personnage" name="image_personnage" accept="image/*">
                    <?php if (!empty($personnage['image_personnage'])): ?>
                        <img src="../data/images/<?= htmlspecialchars($personnage['image_personnage']) ?>" alt="Image personnage" class="tab-indice-img" style="margin-top:8px;">
                        <div><small>Image actuelle : <?= htmlspecialchars($personnage['image_personnage']) ?></small></div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="button button-form">Enregistrer les modifications</button>
            </form>
            <div class="liens-container">
                <a href="list_personnages.php" class="liens">Retour à la liste des personnages</a>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
</body>
</html>
