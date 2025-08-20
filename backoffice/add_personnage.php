<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/personnages.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom_personnage'];
    $description = $_POST['description_personnage'];
    $image = '';
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
        add_personnage($pdo, $nom, $description, $image);
        header('Location: personnages.php');
        exit();
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
    <title>Ajouter une personnalité</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <h1 class="h1-sticky">Nouvelle personnalité</h1>
    <main>
        <div class="form-container">
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" class="formulaire">
                <div class="form-group">
                    <label for="nom_personnage">Nom :</label>
                    <input type="text" id="nom_personnage" name="nom_personnage" required>
                </div>
                <div class="form-group">
                    <label for="description_personnage">Description :</label>
                    <textarea id="description_personnage" name="description_personnage" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image_personnage">Image :</label>
                    <input type="file" id="image_personnage" name="image_personnage" accept="image/*">
                </div>
                <button type="submit" class="button button-form">Ajouter</button>
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