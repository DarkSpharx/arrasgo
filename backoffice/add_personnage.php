<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/personnages.php';

// Récupérer tous les parcours pour la sélection
$parcours = [];
$stmt = $pdo->query("SELECT id_parcours, nom_parcours FROM parcours ORDER BY nom_parcours");
if ($stmt) {
    $parcours = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom_personnage'];
    $description = $_POST['description_personnage'];
    $image = '';
    $mp3_personnage = '';
    $parcours_selectionnes = isset($_POST['parcours']) ? $_POST['parcours'] : [];
    if (isset($_FILES['image_personnage']) && $_FILES['image_personnage']['error'] == UPLOAD_ERR_OK) {
        $img_name = uniqid() . '_' . basename($_FILES['image_personnage']['name']);
        $img_path = __DIR__ . '/../data/images/' . $img_name;
        if (move_uploaded_file($_FILES['image_personnage']['tmp_name'], $img_path)) {
            $image = $img_name;
        } else {
            $error = "Erreur lors de l'upload de l'image.";
        }
    }
    // Gestion du MP3 personnage
    if (isset($_FILES['mp3_personnage']) && $_FILES['mp3_personnage']['error'] == UPLOAD_ERR_OK) {
        $mp3_name = uniqid() . '_' . basename($_FILES['mp3_personnage']['name']);
        $mp3_path = __DIR__ . '/../data/mp3/' . $mp3_name;
        if (move_uploaded_file($_FILES['mp3_personnage']['tmp_name'], $mp3_path)) {
            $mp3_personnage = $mp3_name;
        } else {
            $error = "Erreur lors de l'upload du fichier audio.";
        }
    }
    if (!empty($nom) && !empty($description) && !empty($parcours_selectionnes)) {
        add_personnage($pdo, $nom, $description, $image, $mp3_personnage);
        $id_personnage = $pdo->lastInsertId();
        // Lier le personnage aux parcours sélectionnés
        $stmt = $pdo->prepare("INSERT INTO parcours_personnages (id_parcours, id_personnage) VALUES (?, ?)");
        foreach ($parcours_selectionnes as $id_parcours) {
            $stmt->execute([$id_parcours, $id_personnage]);
        }
        header('Location: list_personnages.php');
        exit();
    } else {
        $error = "Le nom, la description et au moins un parcours sont obligatoires.";
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
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/header_footer.css">
    <script src="js/admin.js" defer></script>
    <title>Ajouter une personnalité</title>
    <link rel="stylesheet" href="css/alertes.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <h1 class="h1-sticky">Nouvelle personnalité</h1>
    <main>
        <div class="form-container">
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form action="add_personnage.php" method="POST" enctype="multipart/form-data">
                <a href="list_personnages.php" class="liens">🔙 Retour à la liste des personnalités</a>

                <div class="form-group-horizontal">
                    <label for="nom_personnage">Nom :</label>
                    <input type="text" id="nom_personnage" name="nom_personnage" required>
                </div>

                <div class="form-group-horizontal">
                    <label for="description_personnage">Description :</label>
                    <textarea id="description_personnage" name="description_personnage" required></textarea>
                </div>


                <div class="form-group-horizontal">
                    <label for="image_personnage">Image :</label>
                    <input type="file" id="image_personnage" name="image_personnage" accept="image/*" style="display:none;">
                    <label for="image_personnage" class="button-form">Choisir un fichier</label>
                    <span id="file-chosen">Aucun fichier choisi</span>
                </div>

                <div class="form-group-horizontal">
                    <label for="mp3_personnage">Audio (MP3) :</label>
                    <input type="file" id="mp3_personnage" name="mp3_personnage" accept="audio/mp3,audio/mpeg" style="display:none;">
                    <label for="mp3_personnage" class="button-form">Choisir un fichier audio</label>
                    <span id="mp3-file-chosen">Aucun fichier choisi</span>
                </div>

                <div class="form-group-horizontal">
                    <label>Parcours liés :</label>
                    <div style="display:flex; flex-wrap:wrap; gap:12px;">
                        <?php foreach ($parcours as $p): ?>
                            <label style="display:flex;align-items:center;gap:4px;">
                                <input type="checkbox" name="parcours[]" value="<?= $p['id_parcours'] ?>">
                                <?= htmlspecialchars($p['nom_parcours']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <small>Sélectionnez un ou plusieurs parcours</small>
                </div>

                <button class="button" type="submit">Ajouter la personnalité</button>
            </form>
            <script>
                document.getElementById('image_personnage').addEventListener('change', function() {
                    document.getElementById('file-chosen').textContent = this.files[0]?.name || 'Aucun fichier choisi';
                });
                document.getElementById('mp3_personnage').addEventListener('change', function() {
                    document.getElementById('mp3-file-chosen').textContent = this.files[0]?.name || 'Aucun fichier choisi';
                });
            </script>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
</body>

</html>