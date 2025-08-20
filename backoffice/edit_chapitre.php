<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/chapitres.php';

$id_chapitre = isset($_GET['id']) ? intval($_GET['id']) : 0;
$chapitre = get_chapitre($pdo, $id_chapitre);
if (!$chapitre) {
    header('Location: list_chapitres.php');
    exit();
}
$id_etape = $chapitre['id_etape'];
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre_chapitre'];
    $texte = $_POST['texte_chapitre'];
    $ordre = $_POST['ordre_chapitre'];
    $image = $chapitre['image_chapitre'];

    // Gestion de l'image
    if (isset($_FILES['image_chapitre']) && $_FILES['image_chapitre']['error'] == UPLOAD_ERR_OK) {
        $img_name = uniqid() . '_' . basename($_FILES['image_chapitre']['name']);
        $img_path = __DIR__ . '/../data/images/' . $img_name;
        if (move_uploaded_file($_FILES['image_chapitre']['tmp_name'], $img_path)) {
            $image = $img_name;
        } else {
            $error = "Erreur lors de l'upload de l'image.";
        }
    }

    if (!empty($titre) && !empty($texte)) {
        $result = update_chapitre($pdo, $id_chapitre, $titre, $texte, $ordre, $image);
        if ($result) {
            $success = true;
            // Rafraîchir les données du chapitre
            $chapitre = get_chapitre($pdo, $id_chapitre);
        } else {
            $error = "Erreur lors de la mise à jour du chapitre.";
        }
    } else {
        $error = "Le titre et le texte sont obligatoires.";
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
    <title>Modifier un chapitre</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <h1 class="h1-sticky">Modifier le chapitre</h1>
    <main>
        <div class="form-container">
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php elseif ($success): ?>
                <div class="success">Chapitre mis à jour avec succès.</div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" class="formulaire">
                <div class="form-group">
                    <label for="titre_chapitre">Titre du chapitre :</label>
                    <input type="text" id="titre_chapitre" name="titre_chapitre" value="<?= htmlspecialchars($chapitre['titre_chapitre']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="texte_chapitre">Texte du chapitre :</label>
                    <textarea id="texte_chapitre" name="texte_chapitre" required><?= htmlspecialchars($chapitre['texte_chapitre']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="ordre_chapitre">Ordre :</label>
                    <input type="number" id="ordre_chapitre" name="ordre_chapitre" value="<?= htmlspecialchars($chapitre['ordre_chapitre']) ?>" min="1">
                </div>
                <div class="form-group">
                    <label for="image_chapitre">Image :</label>
                    <input type="file" id="image_chapitre" name="image_chapitre" accept="image/*">
                    <?php if (!empty($chapitre['image_chapitre'])): ?>
                        <img src="../data/images/<?= htmlspecialchars($chapitre['image_chapitre']) ?>" alt="Image chapitre" class="tab-indice-img" style="margin-top:8px;">
                        <div><small>Image actuelle : <?= htmlspecialchars($chapitre['image_chapitre']) ?></small></div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="button button-form">Enregistrer les modifications</button>
            </form>
            <div class="liens-container">
                <a href="list_chapitres.php?id_etape=<?= $id_etape ?>" class="liens">Retour à la liste des chapitres</a>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
</body>
</html>
