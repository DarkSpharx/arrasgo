<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/etapes.php';

$id_parcours = isset($_GET['id_parcours']) ? intval($_GET['id_parcours']) : 0;
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre_etape'];
    $indice_texte = $_POST['indice_etape_texte'];
    $question = $_POST['question_etape'];
    $reponse = $_POST['reponse_attendue'];
    $lat = $_POST['latitude'];
    $lng = $_POST['longitude'];

    $lat = ($lat === '' ? null : $lat);
    $lng = ($lng === '' ? null : $lng);
    $ordre = $_POST['ordre_etape'];

    // Gestion du MP3
    $mp3 = '';
    if (isset($_FILES['mp3_etape']) && $_FILES['mp3_etape']['error'] == UPLOAD_ERR_OK) {
        $mp3_name = uniqid() . '_' . basename($_FILES['mp3_etape']['name']);
        $mp3_path = __DIR__ . '/../data/mp3/' . $mp3_name;
        if (move_uploaded_file($_FILES['mp3_etape']['tmp_name'], $mp3_path)) {
            $mp3 = $mp3_name;
        }
    }

    // Gestion de l'image
    $image = '';
    if (isset($_FILES['indice_etape_image']) && $_FILES['indice_etape_image']['error'] == UPLOAD_ERR_OK) {
        $img_name = uniqid() . '_' . basename($_FILES['indice_etape_image']['name']);
        $img_path = __DIR__ . '/../data/images/' . $img_name;
        if (move_uploaded_file($_FILES['indice_etape_image']['tmp_name'], $img_path)) {
            $image = $img_name;
        }
    }

    // Gestion de l'image header
    $image_header = '';
    if (isset($_FILES['image_header']) && $_FILES['image_header']['error'] == UPLOAD_ERR_OK) {
        $img_name = uniqid() . '_' . basename($_FILES['image_header']['name']);
        $img_path = __DIR__ . '/../data/images/' . $img_name;
        if (move_uploaded_file($_FILES['image_header']['tmp_name'], $img_path)) {
            $image_header = $img_name;
        }
    }

    // Gestion de l'image question
    $image_question = '';
    if (isset($_FILES['image_question']) && $_FILES['image_question']['error'] == UPLOAD_ERR_OK) {
        $img_name = uniqid() . '_' . basename($_FILES['image_question']['name']);
        $img_path = __DIR__ . '/../data/images/' . $img_name;
        if (move_uploaded_file($_FILES['image_question']['tmp_name'], $img_path)) {
            $image_question = $img_name;
        }
    }

    if (!empty($titre)) {
        add_etape(
            $pdo,
            $id_parcours,
            $titre,
            $mp3,
            $image_header,
            $image_question,
            $indice_texte,
            $image,
            $question,
            $reponse,
            $lat,
            $lng,
            $ordre,
            'reponse'
        );
        header('Location: list_etapes.php?id_parcours=' . $id_parcours);
        exit();
    } else {
        $error = "Le titre est obligatoire.";
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
    <title>Ajouter une étape</title>
    <link rel="stylesheet" href="css/alertes.css">
    <link rel="icon" type="image/png" href="assets/favicon/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="assets/favicon/favicon.svg">
    <link rel="shortcut icon" href="assets/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-title" content="Arras Go Backoffice">
    <link rel="manifest" href="assets/favicon/site.webmanifest">
</head>

<body>
    <?php include 'header.php'; ?>
    <h1 class="h1-sticky">Nouvelle étape</h1>
    <main>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <div class="form-container">
            <form action="add_etape.php?id_parcours=<?= $id_parcours ?>" method="POST" enctype="multipart/form-data">
                <a href="list_etapes.php?id_parcours=<?= $id_parcours ?>" class="liens">🔙 Retour à la liste des étapes</a>

                <div class="form-group-horizontal">
                    <label for="titre_etape">Titre de l'étape :</label>
                    <input type="text" id="titre_etape" name="titre_etape" required>
                </div>

                <div class="form-group-horizontal">
                    <label for="mp3_etape">Fichier MP3 :</label>
                    <input type="file" id="mp3_etape" name="mp3_etape" accept=".mp3" style="display:none;">
                    <label for="mp3_etape" class="button-form">Choisir un fichier MP3</label>
                    <span id="file-chosen-mp3">Aucun fichier choisi</span>
                </div>

                <div class="form-group-horizontal">
                    <label for="indice_etape_texte">Indice texte :</label>
                    <input type="text" id="indice_etape_texte" name="indice_etape_texte">
                </div>

                <div class="form-group-horizontal">
                    <label for="indice_etape_image">Indice image :</label>
                    <input type="file" id="indice_etape_image" name="indice_etape_image" accept="image/*" style="display:none;">
                    <label for="indice_etape_image" class="button-form">Choisir une image</label>
                    <span id="file-chosen-indice">Aucun fichier choisi</span>
                </div>

                <div class="form-group-horizontal">
                    <label for="question_etape">Question :</label>
                    <input type="text" id="question_etape" name="question_etape">
                </div>

                <div class="form-group-horizontal">
                    <label for="reponse_attendue">Réponse attendue :</label>
                    <input type="text" id="reponse_attendue" name="reponse_attendue">
                </div>

                <div class="form-group-horizontal">
                    <label for="latitude">Latitude :</label>
                    <input type="text" id="latitude" name="latitude">
                </div>

                <div class="form-group-horizontal">
                    <label for="longitude">Longitude :</label>
                    <input type="text" id="longitude" name="longitude">
                </div>

                <div class="form-group-horizontal">
                    <label for="ordre_etape">Ordre :</label>
                    <?php
                    // Récupérer tous les ordres déjà utilisés pour ce parcours
                    $ordres_utilises = [];
                    $stmt = $pdo->prepare("SELECT ordre_etape FROM etapes WHERE id_parcours = ? ORDER BY ordre_etape");
                    $stmt->execute([$id_parcours]);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $ordres_utilises[] = (int)$row['ordre_etape'];
                    }
                    // Calculer le nombre total d'étapes pour le parcours (avant ajout)
                    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM etapes WHERE id_parcours = ?");
                    $stmt2->execute([$id_parcours]);
                    $nb_etapes = (int)$stmt2->fetchColumn();
                    $max_ordre = $nb_etapes + 1;
                    ?>
                    <select id="ordre_etape" name="ordre_etape" required>
                        <?php for ($i = 1; $i <= $max_ordre; $i++): ?>
                            <option value="<?= $i ?>" <?= in_array($i, $ordres_utilises) ? 'disabled' : '' ?>><?= $i ?><?= in_array($i, $ordres_utilises) ? ' (occupé)' : '' ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group-horizontal">
                    <label for="image_header">Image d'illustration de la page étape :</label>
                    <input type="file" id="image_header" name="image_header" accept="image/*" style="display:none;">
                    <label for="image_header" class="button-form">Choisir une image</label>
                    <span id="file-chosen-header">Aucun fichier choisi</span>
                </div>

                <div class="form-group-horizontal">
                    <label for="image_question">Image d'illustration de la page question :</label>
                    <input type="file" id="image_question" name="image_question" accept="image/*" style="display:none;">
                    <label for="image_question" class="button-form">Choisir une image</label>
                    <span id="file-chosen-question">Aucun fichier choisi</span>
                </div>

                <button class="button" type="submit">Ajouter l'étape</button>
            </form>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
    <script>
        document.getElementById('mp3_etape').addEventListener('change', function() {
            document.getElementById('file-chosen-mp3').textContent = this.files[0]?.name || 'Aucun fichier choisi';
        });
        document.getElementById('indice_etape_image').addEventListener('change', function() {
            document.getElementById('file-chosen-indice').textContent = this.files[0]?.name || 'Aucun fichier choisi';
        });
        document.getElementById('image_header').addEventListener('change', function() {
            document.getElementById('file-chosen-header').textContent = this.files[0]?.name || 'Aucun fichier choisi';
        });
        document.getElementById('image_question').addEventListener('change', function() {
            document.getElementById('file-chosen-question').textContent = this.files[0]?.name || 'Aucun fichier choisi';
        });
    </script>
</body>

</html>