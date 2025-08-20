<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/etapes.php';

$id_etape = isset($_GET['id']) ? intval($_GET['id']) : 0;
$etape = get_etape($pdo, $id_etape);
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre_etape'];
    $indice_texte = $_POST['indice_etape_texte'];
    $question = $_POST['question_etape'];
    $reponse = $_POST['reponse_attendue'];
    $lat = $_POST['latitude'];
    $lng = $_POST['longitude'];
    $ordre = $_POST['ordre_etape'];
    $type_validation = $etape['type_validation'];

    // Gestion du MP3
    $mp3 = $etape['mp3_etape'];
    if (isset($_FILES['mp3_etape']) && $_FILES['mp3_etape']['error'] == UPLOAD_ERR_OK) {
        $mp3_name = uniqid() . '_' . basename($_FILES['mp3_etape']['name']);
        $mp3_path = __DIR__ . '/../data/mp3/' . $mp3_name;
        if (move_uploaded_file($_FILES['mp3_etape']['tmp_name'], $mp3_path)) {
            $mp3 = $mp3_name;
        }
    }

    // Gestion de l'image
    $image = $etape['indice_etape_image'];
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
        // Si pas de nouvel upload, on garde l'ancienne valeur
        if (empty($image_header)) $image_header = $etape['image_header'] ?? '';
        if (empty($image_question)) $image_question = $etape['image_question'] ?? '';

        $lat = ($lat === '' ? null : $lat);
        $lng = ($lng === '' ? null : $lng);

        update_etape(
            $pdo,
            $id_etape,
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
            $type_validation
        );
        header('Location: list_etapes.php?id_parcours=' . $etape['id_parcours']);
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
    <link rel="stylesheet" href="css/header_footer.css">
    <script src="js/admin.js" defer></script>
    <title>Modifier une étape</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <h1 class="h1-sticky">Modifier l'étape</h1>
    <main>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <div class="form-container">
            <form method="POST" enctype="multipart/form-data">
                <a href="list_etapes.php?id_parcours=<?= $etape['id_parcours'] ?>" class="liens">🔙 Retour à la liste des étapes</a>
                <div class="form-group-horizontal">
                    <label for="titre_etape">Titre de l'étape :</label>
                    <input type="text" id="titre_etape" name="titre_etape" value="<?= htmlspecialchars($etape['titre_etape']) ?>" required>
                </div>
                <div class="form-group-horizontal">
                    <label for="mp3_etape">Fichier MP3 :</label>
                    <?php if (!empty($etape['mp3_etape'])): ?>
                        <audio src="/data/mp3/<?= htmlspecialchars($etape['mp3_etape']) ?>" controls></audio>
                        <br>
                        <small>Fichier actuel : <?= htmlspecialchars($etape['mp3_etape']) ?></small>
                    <?php endif; ?>
                    <input type="file" id="mp3_etape" name="mp3_etape" accept=".mp3" style="display:none;">
                    <label for="mp3_etape" class="button-form">Choisir un fichier MP3</label>
                    <span id="file-chosen-mp3">Aucun fichier choisi</span>
                </div>
                <div class="form-group-horizontal">
                    <label for="indice_etape_texte">Indice texte :</label>
                    <input type="text" id="indice_etape_texte" name="indice_etape_texte" value="<?= htmlspecialchars($etape['indice_etape_texte']) ?>">
                </div>
                <div class="form-group-horizontal">
                    <label for="indice_etape_image">Indice image :</label>
                    <?php if (!empty($etape['indice_etape_image'])): ?>
                        <img src="/data/images/<?= htmlspecialchars($etape['indice_etape_image']) ?>" alt="Indice image" style="max-width:80px;max-height:80px;">
                        <br>
                        <small>Fichier actuel : <?= htmlspecialchars($etape['indice_etape_image']) ?></small>
                    <?php endif; ?>
                    <input type="file" id="indice_etape_image" name="indice_etape_image" accept="image/*" style="display:none;">
                    <label for="indice_etape_image" class="button-form">Choisir une image</label>
                    <span id="file-chosen-indice">Aucun fichier choisi</span>
                </div>
                <div class="form-group-horizontal">
                    <label for="image_header">Image d'illustration de la page étape :</label>
                    <?php if (!empty($etape['image_header']) && file_exists(__DIR__ . '/../data/images/' . $etape['image_header'])): ?>
                        <img src="/data/images/<?= htmlspecialchars($etape['image_header']) ?>" alt="Image header" style="max-width:80px;max-height:80px;">
                        <br>
                        <small>Fichier actuel : <?= htmlspecialchars($etape['image_header']) ?></small>
                    <?php endif; ?>
                    <input type="file" id="image_header" name="image_header" accept="image/*" style="display:none;" data-current="<?= !empty($etape['image_header']) ? htmlspecialchars($etape['image_header']) : '' ?>">
                    <label for="image_header" class="button-form">Choisir une image</label>
                    <span id="file-chosen-header"><?php if (!empty($etape['image_header'])) {
                                                        echo htmlspecialchars($etape['image_header']);
                                                    } else {
                                                        echo 'Aucun fichier choisi';
                                                    } ?></span>
                </div>
                <div class="form-group-horizontal">
                    <label for="image_question">Image d'illustration de la page question :</label>
                    <?php if (!empty($etape['image_question']) && file_exists(__DIR__ . '/../data/images/' . $etape['image_question'])): ?>
                        <img src="/data/images/<?= htmlspecialchars($etape['image_question']) ?>" alt="Image question" style="max-width:80px;max-height:80px;">
                        <br>
                        <small>Fichier actuel : <?= htmlspecialchars($etape['image_question']) ?></small>
                    <?php endif; ?>
                    <input type="file" id="image_question" name="image_question" accept="image/*" style="display:none;" data-current="<?= !empty($etape['image_question']) ? htmlspecialchars($etape['image_question']) : '' ?>">
                    <label for="image_question" class="button-form">Choisir une image</label>
                    <span id="file-chosen-question"><?php if (!empty($etape['image_question'])) {
                                                        echo htmlspecialchars($etape['image_question']);
                                                    } else {
                                                        echo 'Aucun fichier choisi';
                                                    } ?></span>
                </div>
                <div class="form-group-horizontal">
                    <label for="question_etape">Question :</label>
                    <input type="text" id="question_etape" name="question_etape" value="<?= htmlspecialchars($etape['question_etape']) ?>">
                </div>
                <div class="form-group-horizontal">
                    <label for="reponse_attendue">Réponse attendue :</label>
                    <input type="text" id="reponse_attendue" name="reponse_attendue" value="<?= htmlspecialchars($etape['reponse_attendue']) ?>">
                </div>
                <div class="form-group-horizontal">
                    <label for="latitude">Latitude :</label>
                    <input type="text" id="latitude" name="latitude" value="<?= htmlspecialchars($etape['latitude'] ?? '') ?>">
                </div>
                <div class="form-group-horizontal">
                    <label for="longitude">Longitude :</label>
                    <input type="text" id="longitude" name="longitude" value="<?= htmlspecialchars($etape['longitude'] ?? '') ?>">
                </div>
                <div class="form-group-horizontal">
                    <label for="ordre_etape">Ordre :</label>
                    <input type="number" id="ordre_etape" name="ordre_etape" min="1" value="<?= htmlspecialchars($etape['ordre_etape'] ?? '') ?>">
                </div>
                <button class="button" type="submit">Enregistrer</button>
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
        const inputHeader = document.getElementById('image_header');
        const spanHeader = document.getElementById('file-chosen-header');
        if (inputHeader && spanHeader) {
            const currentHeader = inputHeader.getAttribute('data-current');
            if (currentHeader) {
                spanHeader.textContent = currentHeader;
            } else {
                spanHeader.textContent = 'Aucun fichier choisi';
            }
            inputHeader.addEventListener('change', function() {
                spanHeader.textContent = this.files[0]?.name || (this.getAttribute('data-current') || 'Aucun fichier choisi');
            });
        }
        const inputQuestion = document.getElementById('image_question');
        const spanQuestion = document.getElementById('file-chosen-question');
        if (inputQuestion && spanQuestion) {
            const currentQuestion = inputQuestion.getAttribute('data-current');
            if (currentQuestion) {
                spanQuestion.textContent = currentQuestion;
            } else {
                spanQuestion.textContent = 'Aucun fichier choisi';
            }
            inputQuestion.addEventListener('change', function() {
                spanQuestion.textContent = this.files[0]?.name || (this.getAttribute('data-current') || 'Aucun fichier choisi');
            });
        }
    </script>
</body>

</html>