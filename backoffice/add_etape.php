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

    // Correction : transformer les champs vides en NULL
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

<head>
    <link rel="stylesheet" href="css/style_backoffice.css">
    <script src="js/admin.js" defer></script>
</head>
<?php include 'header.php'; ?>
<?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data" class="form-etape">
    <div class="form-group">
        <label for="titre_etape">Titre de l'étape :</label>
        <input type="text" id="titre_etape" name="titre_etape" required>
    </div>
    <div class="form-group">
        <label for="mp3_etape">Fichier MP3 :</label>
        <input type="file" id="mp3_etape" name="mp3_etape" accept=".mp3">
    </div>
    <div class="form-group">
        <label for="indice_etape_texte">Indice texte :</label>
        <input type="text" id="indice_etape_texte" name="indice_etape_texte">
    </div>
    <div class="form-group">
        <label for="indice_etape_image">Indice image :</label>
        <input type="file" id="indice_etape_image" name="indice_etape_image" accept="image/*">
    </div>
    <div class="form-group">
        <label for="question_etape">Question :</label>
        <input type="text" id="question_etape" name="question_etape">
    </div>
    <div class="form-group">
        <label for="reponse_attendue">Réponse attendue :</label>
        <input type="text" id="reponse_attendue" name="reponse_attendue">
    </div>
    <div class="form-group">
        <label for="latitude">Latitude :</label>
        <input type="text" id="latitude" name="latitude">
    </div>
    <div class="form-group">
        <label for="longitude">Longitude :</label>
        <input type="text" id="longitude" name="longitude">
    </div>
    <div class="form-group">
        <label for="ordre_etape">Ordre :</label>
        <input type="number" id="ordre_etape" name="ordre_etape" min="1">
    </div>
    <div class="form-group">
        <label for="image_header">Image d'illustration de la page étape :</label>
        <input type="file" id="image_header" name="image_header" accept="image/*">
    </div>
    <div class="form-group">
        <label for="image_question">Image d'illustration de la page question :</label>
        <input type="file" id="image_question" name="image_question" accept="image/*">
    </div>
    <button type="submit" class="button">Ajouter l'étape</button>
</form>
<a href="list_etapes.php?id_parcours=<?= $id_parcours ?>">Retour à la liste des étapes</a>
<?php
// Affichage des étapes pour le parcours
$stmt = $pdo->prepare("SELECT * FROM etapes WHERE id_parcours = :id_parcours ORDER BY ordre_etape");
$stmt->execute(['id_parcours' => $id_parcours]);
$etapes = $stmt->fetchAll();

foreach ($etapes as $etape):
?>
    <div class="etape">
        <h3><?= htmlspecialchars($etape['titre_etape'] ?? '') ?></h3>
        <?php if (!empty($etape['indice_etape_image'])): ?>
            <img src="/data/images/<?= htmlspecialchars($etape['indice_etape_image']) ?>" alt="Indice image">
        <?php endif; ?>
        <audio src="/data/mp3/<?= htmlspecialchars($etape['mp3_etape'] ?? '') ?>" controls></audio>
        <p>Indice texte : <?= htmlspecialchars($etape['indice_etape_texte'] ?? '') ?></p>
        <p>Question : <?= htmlspecialchars($etape['question_etape'] ?? '') ?></p>
        <p>Réponse attendue : <?= htmlspecialchars($etape['reponse_attendue'] ?? '') ?></p>
        <p>Latitude : <?= htmlspecialchars($etape['latitude'] ?? '') ?></p>
        <p>Longitude : <?= htmlspecialchars($etape['longitude'] ?? '') ?></p>
        <p>Ordre : <?= htmlspecialchars($etape['ordre_etape'] ?? '') ?></p>
    </div>
<?php endforeach; ?>