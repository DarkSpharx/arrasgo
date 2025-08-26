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
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/header_footer.css">
    <script src="js/admin.js" defer></script>
    <title>Modifier un chapitre</title>
    <link rel="stylesheet" href="css/alertes.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <h1 class="h1-sticky">Modifier <?= htmlspecialchars($chapitre['titre_chapitre']) ?></h1>
    <main>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="success">Chapitre mis à jour avec succès.</div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" enctype="multipart/form-data" class="formulaire" action="edit_chapitre.php?id=<?= $id_chapitre ?>">

                <a href="list_chapitres.php?id_etape=<?= $id_etape ?>" class="liens">🔙 Retour à la liste des chapitres</a>

                <div class="form-group-horizontal">
                    <label for="titre_chapitre">Titre du chapitre</label>
                    <input type="text" id="titre_chapitre" name="titre_chapitre" value="<?= htmlspecialchars($chapitre['titre_chapitre']) ?>" required>
                </div>
                <hr>
                <div class="form-group-horizontal">
                    <label for="texte_chapitre">Texte du chapitre</label>
                    <textarea id="texte_chapitre" name="texte_chapitre" required><?= htmlspecialchars($chapitre['texte_chapitre']) ?></textarea>
                </div>
                <hr>
                <div class="form-group-horizontal form-img">
                    <label for="image_chapitre">Image d'illustration du chapitre</label>
                    <?php if (!empty($chapitre['image_chapitre'])): ?>
                        <img src="../data/images/<?= htmlspecialchars($chapitre['image_chapitre']) ?>" alt="Image chapitre" class="tab-indice-img">
                        <br>
                        <small>Fichier actuel <?= htmlspecialchars($chapitre['image_chapitre']) ?></small>
                    <?php endif; ?>
                    <input type="file" id="image_chapitre" name="image_chapitre" accept="image/*" style="display:none;">
                    <label for="image_chapitre" class="button-form">Choisir un fichier</label>
                    <span id="file-chosen">Aucun fichier choisi</span>
                </div>
                <hr>
                <div class="form-group-horizontal">
                    <label for="ordre_chapitre">Ordre</label>
                    <?php
                    // Récupérer tous les ordres déjà utilisés pour cette étape (hors ce chapitre)
                    $ordres_utilises = [];
                    $stmt = $pdo->prepare("SELECT ordre_chapitre FROM chapitres WHERE id_etape = ? AND id_chapitre != ? ORDER BY ordre_chapitre");
                    $stmt->execute([$id_etape, $id_chapitre]);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $ordres_utilises[] = (int)$row['ordre_chapitre'];
                    }
                    // Calculer le nombre total de chapitres pour l'étape
                    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM chapitres WHERE id_etape = ?");
                    $stmt2->execute([$id_etape]);
                    $nb_chapitres = (int)$stmt2->fetchColumn();
                    // On autorise l'ordre de 1 à nb_chapitres (ou nb_chapitres si nouveau)
                    $max_ordre = max($nb_chapitres, (int)$chapitre['ordre_chapitre']);
                    ?>
                    <select id="ordre_chapitre" name="ordre_chapitre" required>
                        <?php for ($i = 1; $i <= $max_ordre; $i++): ?>
                            <option value="<?= $i ?>" <?= ($i == $chapitre['ordre_chapitre']) ? 'selected' : (in_array($i, $ordres_utilises) ? 'disabled' : '') ?>><?= $i ?><?= in_array($i, $ordres_utilises) && $i != $chapitre['ordre_chapitre'] ? ' (occupé)' : '' ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <button type="submit" class="button">Enregistrer</button>
            </form>

        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>

    <script>
        const input = document.getElementById('image_chapitre');
        const fileChosen = document.getElementById('file-chosen');
        input.addEventListener('change', function() {
            fileChosen.textContent = this.files[0] ? this.files[0].name : 'Aucun fichier choisi';
        });
    </script>
</body>

</html>