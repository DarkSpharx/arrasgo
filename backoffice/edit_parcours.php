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

$stmt = $pdo->prepare("SELECT nom_parcours, description_parcours, image_parcours, statut FROM parcours WHERE id_parcours = ?");
$stmt->execute([$id]);
$parcours = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$parcours) {
    header('Location: list_parcours.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_parcours = $_POST['nom_parcours'];
    $description = $_POST['description'];
    $statut = isset($_POST['statut']) ? intval($_POST['statut']) : 0;

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
        if (update_parcours($pdo, $id, $nom_parcours, $description, $image_parcours, $statut)) {
            $success = true;
            // Recharge les infos modifiées
            $parcours['nom_parcours'] = $nom_parcours;
            $parcours['description_parcours'] = $description;
            $parcours['image_parcours'] = $image_parcours;
            $parcours['statut'] = $statut;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style_backoffice.css">
    <link rel="stylesheet" href="css/header_footer.css">
    <!-- <link rel="stylesheet" href="css/tab.css"> -->
    <script src="js/admin.js" defer></script>
    <title>Modifier un Parcours</title>
    <link rel="stylesheet" href="css/alertes.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <h1 class="h1-sticky">Modifier <?= htmlspecialchars($parcours['nom_parcours']) ?></h1>
    <main>

        <?php if ($success): ?>
            <div class="success">Parcours modifié avec succès.</div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" enctype="multipart/form-data" action="edit_parcours.php?id=<?= $id ?>">

                <a href="list_parcours.php" class="liens">🔙 Retour à la liste des parcours</a>

                <div class="form-group-horizontal">
                    <label for="nom_parcours">Nom du Parcours</label>
                    <input type="text" id="nom_parcours" name="nom_parcours" value="<?= htmlspecialchars($parcours['nom_parcours']) ?>" required>
                </div>
                <div class="form-group-horizontal">
                    <label for="statut">Statut du parcours</label>
                    <select id="statut" name="statut">
                        <option value="0" <?= (isset($parcours['statut']) && $parcours['statut'] == 0) ? 'selected' : '' ?>>Brouillon (hors ligne)</option>
                        <option value="1" <?= (isset($parcours['statut']) && $parcours['statut'] == 1) ? 'selected' : '' ?>>En ligne</option>
                    </select>
                </div>
                <hr>
                <div class="form-group-horizontal form-img">
                    <label for="image_parcours">Image d'illustration du parcours</label>
                    <?php if (!empty($parcours['image_parcours'])): ?>
                        <img src="/data/images/<?= htmlspecialchars($parcours['image_parcours']) ?>" alt="Image parcours">
                        <br>
                        <small>Fichier actuel <?= htmlspecialchars($parcours['image_parcours']) ?></small>
                    <?php endif; ?>
                    <input type="file" id="image_parcours" name="image_parcours" accept="image/*" style="display:none;">
                    <label for="image_parcours" class="button-form">Choisir un fichier</label>
                    <span id="file-chosen">Aucun fichier choisi</span>
                </div>
                <hr>
                <div class="form-group-horizontal">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required><?= htmlspecialchars($parcours['description_parcours']) ?></textarea>
                </div>
                <button class="button" type="submit">Enregistrer</button>
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