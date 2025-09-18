<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/chapitres.php';

// Récupère id_etape depuis POST (prioritaire) ou GET
if (isset($_POST['id_etape'])) {
    $id_etape = intval($_POST['id_etape']);
} else {
    $id_etape = isset($_GET['id_etape']) ? intval($_GET['id_etape']) : 0;
}
// Vérifie que l'étape existe
if ($id_etape > 0) {
    $stmt = $pdo->prepare('SELECT id_etape FROM etapes WHERE id_etape = ?');
    $stmt->execute([$id_etape]);
    if (!$stmt->fetch()) {
        $error = 'Erreur : étape non trouvée en base pour id_etape = ' . htmlspecialchars($id_etape);
    }
} else {
    $error = 'Erreur : id_etape manquant ou invalide (' . htmlspecialchars($id_etape) . ')';
}
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
        try {
            ob_start();
            echo "POST :\n";
            print_r($_POST);
            echo "\nAppel add_chapitre(", $id_etape, ", ", $titre, ", ", $texte, ", ", $ordre, ", ", $image, ")\n";
            $result = add_chapitre($pdo, $id_etape, $titre, $texte, $ordre, $image);
            echo "Résultat insertion : ";
            var_dump($result);
            if (!$result) {
                $errorInfo = $pdo->errorInfo();
                echo "\nErreur SQL : ";
                print_r($errorInfo);
            }
            $chapitres_debug = get_chapitres_by_etape($pdo, $id_etape);
            echo "\nCHAPITRES DEBUG :\n";
            print_r($chapitres_debug);
            $debug_messages[] = '<pre style="background:#fff;color:#000;z-index:9999;position:relative;">' . htmlspecialchars(ob_get_clean()) . '</pre>';
            if ($result) {
                // Redirection automatique vers la liste des chapitres
                header('Location: list_chapitres.php?id_etape=' . $id_etape);
                exit();
            } else {
                $debug_messages[] = '<div style="background: #f8d7da; color: #721c24; padding: 16px; border-radius: 8px; margin: 24px 0;">Erreur lors de l\'ajout du chapitre. Copiez le bloc de debug ci-dessus et envoyez-le moi.</div>';
            }
        } catch (PDOException $e) {
            $debug_messages[] = '<div style="background: #f8d7da; color: #721c24; padding: 16px; border-radius: 8px; margin: 24px 0;">Erreur PDO : ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        $error = "Le titre et le texte du chapitre sont obligatoires.";
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
    <title>Ajouter un Chapitre</title>
    <link rel="stylesheet" href="css/alertes.css">
    <link rel="stylesheet" href="css/alertes.css">
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
                <input type="hidden" name="id_etape" value="<?= htmlspecialchars($id_etape) ?>">
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
                    <?php
                    // Récupérer tous les ordres déjà utilisés pour cette étape
                    $ordres_utilises = [];
                    $stmt = $pdo->prepare("SELECT ordre_chapitre FROM chapitres WHERE id_etape = ? ORDER BY ordre_chapitre");
                    $stmt->execute([$id_etape]);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $ordres_utilises[] = (int)$row['ordre_chapitre'];
                    }
                    // Calculer le nombre total de chapitres pour l'étape (avant ajout)
                    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM chapitres WHERE id_etape = ?");
                    $stmt2->execute([$id_etape]);
                    $nb_chapitres = (int)$stmt2->fetchColumn();
                    $max_ordre = $nb_chapitres + 1;
                    ?>
                    <select id="ordre_chapitre" name="ordre_chapitre" required>
                        <?php for ($i = 1; $i <= $max_ordre; $i++): ?>
                            <option value="<?= $i ?>" <?= in_array($i, $ordres_utilises) ? 'disabled' : '' ?>><?= $i ?><?= in_array($i, $ordres_utilises) ? ' (occupé)' : '' ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <button type="submit" class="button">Ajouter le chapitre</button>
            </form>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
    <script>
        document.getElementById('image_chapitre').addEventListener('change', function() {
            document.getElementById('file-chosen').textContent = this.files[0]?.name || 'Aucun fichier choisi';
        });
    </script>
</body>

</html>