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

$id_personnage = isset($_GET['id']) ? intval($_GET['id']) : 0;
$personnage = get_personnage($pdo, $id_personnage);
if (!$personnage) {
    header('Location: list_personnages.php');
    exit();
}
// Récupérer les parcours liés à ce personnage
$parcours_lies = [];
$stmt = $pdo->prepare("SELECT id_parcours FROM parcours_personnages WHERE id_personnage = ?");
$stmt->execute([$id_personnage]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $parcours_lies[] = $row['id_parcours'];
}
$error = '';
$success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom_personnage'];
    $description = $_POST['description_personnage'];
    $image = $personnage['image_personnage'];
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
    if (!empty($nom) && !empty($description) && !empty($parcours_selectionnes)) {
        $result = update_personnage($pdo, $id_personnage, $nom, $description, $image);
        if ($result) {
            // Mettre à jour les liens parcours_personnages
            $pdo->prepare("DELETE FROM parcours_personnages WHERE id_personnage = ?")->execute([$id_personnage]);
            $stmt = $pdo->prepare("INSERT INTO parcours_personnages (id_parcours, id_personnage) VALUES (?, ?)");
            foreach ($parcours_selectionnes as $id_parcours) {
                $stmt->execute([$id_parcours, $id_personnage]);
            }
            $success = true;
            $personnage = get_personnage($pdo, $id_personnage);
            $parcours_lies = $parcours_selectionnes;
        } else {
            $error = "Erreur lors de la mise à jour du personnage.";
        }
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
    <title>Modifier une personnalité</title>
    <link rel="stylesheet" href="css/alertes.css">
    <link rel="stylesheet" href="css/alertes.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <h1 class="h1-sticky">Modifier <?= htmlspecialchars($personnage['nom_personnage']) ?></h1>

    <main>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="success">Personnage mis à jour avec succès.</div>
        <?php endif; ?>
        <div class="form-container">

            <form method="POST" enctype="multipart/form-data" action="edit_personnage.php?id=<?= $id_personnage ?>">
                <a href="list_personnages.php" class="liens">🔙 Retour à la liste des personnalités</a>

                <div class="form-group-horizontal">
                    <label for="nom_personnage">Nom</label>
                    <input type="text" id="nom_personnage" name="nom_personnage" value="<?= htmlspecialchars($personnage['nom_personnage']) ?>" required>
                </div>
                <hr>
                <div class="form-group-horizontal">
                    <label for="description_personnage">Description</label>
                    <textarea id="description_personnage" name="description_personnage" required><?= htmlspecialchars($personnage['description_personnage']) ?></textarea>
                </div>
                <hr>
                <div class="form-group-horizontal form-img">
                    <label for="image_personnage">Image</label>
                    <div id="image-preview-container">
                        <?php if (!empty($personnage['image_personnage'])): ?>
                            <img id="image-preview" src="../data/images/<?= htmlspecialchars($personnage['image_personnage']) ?>" alt="Image personnage" class="tab-indice-img">
                            <br>
                            <small id="current-image-name">Image actuelle <?= htmlspecialchars($personnage['image_personnage']) ?></small>
                        <?php else: ?>
                            <img id="image-preview" src="" alt="Aperçu image" class="tab-indice-img" style="display:none;">
                        <?php endif; ?>
                    </div>
                    <input type="file" id="image_personnage" name="image_personnage" accept="image/*" style="display:none;">
                    <label for="image_personnage" class="button-form">Choisir un fichier</label>
                    <span id="file-chosen">Aucun fichier choisi</span>
                </div>
                <hr>
                <div class="form-group">
                    <label>Parcours liés</label>
                    <small>Sélectionnez un ou plusieurs parcours</small>
                    <div>
                        <?php foreach ($parcours as $p): ?>
                            <label>
                                <input type="checkbox" name="parcours[]" value="<?= $p['id_parcours'] ?>" <?= in_array($p['id_parcours'], $parcours_lies) ? 'checked' : '' ?>>
                                <?= htmlspecialchars($p['nom_parcours']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>

                </div>

                <button class="button" type="submit">Enregistrer</button>
            </form>

        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('image_personnage');
        const fileChosen = document.getElementById('file-chosen');
        const preview = document.getElementById('image-preview');
        const currentImageName = document.getElementById('current-image-name');
        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                fileChosen.textContent = this.files[0].name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
                if (currentImageName) currentImageName.style.display = 'none';
            } else {
                fileChosen.textContent = 'Aucun fichier choisi';
                if (currentImageName) currentImageName.style.display = '';
            }
        });
    });
    </script>
</body>

</html>