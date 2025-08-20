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
    <link rel="stylesheet" href="css/header_footer.css">
    <script src="js/admin.js" defer></script>
    <title>Modifier une personnalité</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <h1 class="h1-sticky">Modifier la personnalité</h1>
    <main>
        <div class="form-container">
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php elseif ($success): ?>
                <div class="success">Personnage mis à jour avec succès.</div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <a href="list_personnages.php" class="liens">🔙 Retour à la liste des personnalités</a>

                <div class="form-group-horizontal">
                    <label for="nom_personnage">Nom :</label>
                    <input type="text" id="nom_personnage" name="nom_personnage" value="<?= htmlspecialchars($personnage['nom_personnage']) ?>" required>
                </div>

                <div class="form-group-horizontal">
                    <label for="description_personnage">Description :</label>
                    <textarea id="description_personnage" name="description_personnage" required><?= htmlspecialchars($personnage['description_personnage']) ?></textarea>
                </div>

                <div class="form-group-horizontal">
                    <label for="image_personnage">Image :</label>
                    <?php if (!empty($personnage['image_personnage'])): ?>
                        <img src="../data/images/<?= htmlspecialchars($personnage['image_personnage']) ?>" alt="Image personnage" class="tab-indice-img" style="margin-top:8px;max-width:80px;max-height:80px;">
                        <br>
                        <small>Image actuelle : <?= htmlspecialchars($personnage['image_personnage']) ?></small>
                    <?php endif; ?>
                    <input type="file" id="image_personnage" name="image_personnage" accept="image/*" style="display:none;">
                    <label for="image_personnage" class="button-form">Choisir un fichier</label>
                    <span id="file-chosen">Aucun fichier choisi</span>
                </div>

                <div class="form-group-horizontal">
                    <label>Parcours liés :</label>
                    <div style="display:flex; flex-wrap:wrap; gap:12px;">
                        <?php foreach ($parcours as $p): ?>
                            <label style="display:flex;align-items:center;gap:4px;">
                                <input type="checkbox" name="parcours[]" value="<?= $p['id_parcours'] ?>" <?= in_array($p['id_parcours'], $parcours_lies) ? 'checked' : '' ?>>
                                <?= htmlspecialchars($p['nom_parcours']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <small>Sélectionnez un ou plusieurs parcours</small>
                </div>

                <button class="button" type="submit">Enregistrer</button>
            </form>
            <script>
                document.getElementById('image_personnage').addEventListener('change', function() {
                    document.getElementById('file-chosen').textContent = this.files[0]?.name || 'Aucun fichier choisi';
                });
            </script>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
</body>

</html>