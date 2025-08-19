<?php
session_start();
require_once __DIR__ . '/../backend/config/database.php';
require_once '../backend/functions/parcours.php';
require_once __DIR__ . '/../backend/security/check_auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_parcours = $_POST['nom_parcours'];
    $description = $_POST['description'];
    $id_user = $_SESSION['admin_id']; // récupère l'id de l'admin connecté

    // Gestion de l'image
    $image_parcours = '';
    if (isset($_FILES['image_parcours']) && $_FILES['image_parcours']['error'] == UPLOAD_ERR_OK) {
        $img_name = uniqid() . '_' . basename($_FILES['image_parcours']['name']);
        $img_path = __DIR__ . '/../data/images/' . $img_name;
        if (move_uploaded_file($_FILES['image_parcours']['tmp_name'], $img_path)) {
            $image_parcours = $img_name;
        }
    }

    if (!empty($nom_parcours) && !empty($description)) {
        $result = add_parcours($pdo, $id_user, $nom_parcours, $description, $image_parcours);
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
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style_backoffice.css">
    <link rel="stylesheet" href="css/header_footer.css">
    <script src="js/admin.js" defer></script>
    <title>Ajouter un Parcours</title>
</head>

<body>
    <?php include 'header.php'; ?>

    <h1 class="h1-sticky">Ajouter un Nouveau Parcours</h1>
    <main>

        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="form-container">
            <form action="add_parcours.php" method="POST" enctype="multipart/form-data">
                <a href="list_parcours.php" class="liens">🔙 Retour à la liste des parcours</a>

                <div class="form-group-horizontal">
                    <label for="nom_parcours">Nom du Parcours:</label>
                    <input type="text" id="nom_parcours" name="nom_parcours" required>
                </div>

                <div class="form-group-horizontal">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <div class="form-group-horizontal">
                    <label for="image_parcours">Image d'illustration du parcours :</label>
                    <input type="file" id="image_parcours" name="image_parcours" accept="image/*" style="display:none;">
                    <label for="image_parcours" class="button-form">Choisir un fichier</label>
                    <span id="file-chosen">Aucun fichier choisi</span>
                </div>

                <button class="button" type="submit">Ajouter</button>
            </form>
        </div>

    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>

    <script>
        document.getElementById('image_parcours').addEventListener('change', function() {
            document.getElementById('file-chosen').textContent = this.files[0]?.name || 'Aucun fichier choisi';
        });
    </script>
</body>

</html>