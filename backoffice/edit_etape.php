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

    if (!empty($titre)) {
        update_etape($pdo, $id_etape, $titre, $mp3, $indice_texte, $image, $question, $reponse, $lat, $lng, $ordre, $type_validation);
        header('Location: list_etapes.php?id_parcours=' . $etape['id_parcours']);
        exit();
    } else {
        $error = "Le titre est obligatoire.";
    }
}
?>
<?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data" class="form-etape">
    <div class="form-group">
        <label for="titre_etape">Titre de l'étape :</label>
        <input type="text" id="titre_etape" name="titre_etape" value="<?= htmlspecialchars($etape['titre_etape']) ?>" required>
    </div>

    <div class="form-group">
        <label for="mp3_etape">Fichier MP3 :</label>
        <?php if (!empty($etape['mp3_etape'])): ?>
            <audio src="/data/mp3/<?= htmlspecialchars($etape['mp3_etape']) ?>" controls></audio>
            <br>
            <small>Fichier actuel : <?= htmlspecialchars($etape['mp3_etape']) ?></small>
        <?php endif; ?>
        <input type="file" id="mp3_etape" name="mp3_etape" accept=".mp3">
    </div>

    <div class="form-group">
        <label for="indice_etape_texte">Indice texte :</label>
        <input type="text" id="indice_etape_texte" name="indice_etape_texte" value="<?= htmlspecialchars($etape['indice_etape_texte']) ?>">
    </div>

    <div class="form-group">
        <label for="indice_etape_image">Indice image :</label>
        <?php if (!empty($etape['indice_etape_image'])): ?>
            <img src="/data/images/<?= htmlspecialchars($etape['indice_etape_image']) ?>" alt="Indice image" style="max-width:80px;max-height:80px;">
            <br>
            <small>Fichier actuel : <?= htmlspecialchars($etape['indice_etape_image']) ?></small>
        <?php endif; ?>
        <input type="file" id="indice_etape_image" name="indice_etape_image" accept="image/*">
    </div>

    <div class="form-group">
        <label for="question_etape">Question :</label>
        <input type="text" id="question_etape" name="question_etape" value="<?= htmlspecialchars($etape['question_etape']) ?>">
    </div>

    <div class="form-group">
        <label for="reponse_attendue">Réponse attendue :</label>
        <input type="text" id="reponse_attendue" name="reponse_attendue" value="<?= htmlspecialchars($etape['reponse_attendue']) ?>">
    </div>

    <div class="form-group">
        <label for="latitude">Latitude :</label>
        <input type="text" id="latitude" name="latitude" value="<?= htmlspecialchars($etape['latitude'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label for="longitude">Longitude :</label>
        <input type="text" id="longitude" name="longitude" value="<?= htmlspecialchars($etape['longitude'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label for="ordre_etape">Ordre :</label>
        <input type="number" id="ordre_etape" name="ordre_etape" min="1" value="<?= htmlspecialchars($etape['ordre_etape'] ?? '') ?>">
    </div>

    <button type="submit" class="button">Enregistrer les modifications</button>
</form>
<a href="list_etapes.php?id_parcours=<?= $etape['id_parcours'] ?>">Retour à la liste des étapes</a>
