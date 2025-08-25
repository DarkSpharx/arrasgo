<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/chapitres.php';

$id_etape = isset($_GET['id_etape']) ? intval($_GET['id_etape']) : 0;
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre_chapitre'];
    $texte = $_POST['texte_chapitre'];
    $ordre = $_POST['ordre_chapitre'];
    $image = '';

    // Gestion de l'image
    if (isset($_FILES['image_chapitre']) && $_FILES['image_chapitre']['error'] == UPLOAD_ERR_OK) {
        $img_name = uniqid() . '_' . basename($_FILES['image_chapitre']['name']);
        $img_path = __DIR__ . '/../data/images/' . $img_name;
        if (move_uploaded_file($_FILES['image_chapitre']['tmp_name'], $img_path)) {
            $image = $img_name;
        }
    }

    if (!empty($texte)) {
        add_chapitre($pdo, $id_etape, $titre, $texte, $ordre, $image);
        header('Location: list_chapitres.php?id_etape=' . $id_etape);
        exit();
    } else {
        $error = "Le texte du chapitre est obligatoire.";
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
    <title>Ajouter un Chapitre</title>
</head>

<body>

    <?php include 'header.php'; ?>

    <h1 class="h1-sticky">Nouveau chapitre</h1>
    <main>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" enctype="multipart/form-data" action="add_chapitre.php">
                <a href="list_chapitres.php?id_etape=<?= $id_etape ?>">🔙 Retour à la liste des chapitres</a>

                <div class="form-group-horizontal">
                    <label for="titre_chapitre">Titre du chapitre :</label>
                    <input type="text" id="titre_chapitre" name="titre_chapitre">
                </div>

                <div class="form-group-horizontal">
                    <label for="texte_chapitre">Texte du chapitre :</label>
                    <textarea id="texte_chapitre" name="texte_chapitre" required></textarea>
                </div>

                <div class="form-group-horizontal">
                    <label for="image_chapitre">Image du chapitre :</label>
                    <input type="file" id="image_chapitre" name="image_chapitre" accept="image/*" style="display:none;">
                    <label for="image_chapitre" class="button-form">Choisir un fichier</label>
                    <span id="file-chosen">Aucun fichier choisi</span>
                </div>

                <div class="form-group-horizontal">
                    <label for="ordre_chapitre">Ordre :</label>
                    <input type="number" id="ordre_chapitre" name="ordre_chapitre" min="1">
                </div>

                <button type="submit" class="button">Ajouter le chapitre</button>
            </form>
        </div>


    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
</body>

</html>