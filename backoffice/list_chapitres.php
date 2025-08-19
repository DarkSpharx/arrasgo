<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/chapitres.php';

$id_etape = isset($_GET['id_etape']) ? intval($_GET['id_etape']) : 0;
// Récupérer l'id_parcours pour le lien retour
$stmt = $pdo->prepare("SELECT id_parcours FROM etapes WHERE id_etape = ?");
$stmt->execute([$id_etape]);
$id_parcours = $stmt->fetchColumn();

$chapitres = get_chapitres_by_etape($pdo, $id_etape);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des chapitres</title>
    <link rel="stylesheet" href="css/style_backoffice.css">
    <script src="js/admin.js" defer></script>
</head>

<body>
    <?php include 'header.php'; ?>
    <h1>Chapitres de l'étape</h1>
    <a href="add_chapitre.php?id_etape=<?= $id_etape ?>">Ajouter un chapitre</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Texte</th>
                <th>Image</th>
                <th>Ordre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chapitres as $c): ?>
                <tr>
                    <td><?= $c['id_chapitre'] ?></td>
                    <td><?= htmlspecialchars($c['titre_chapitre']) ?></td>
                    <td><?= htmlspecialchars($c['texte_chapitre']) ?></td>
                    <td>
                        <?php if (!empty($c['image_chapitre'])): ?>
                            <img src="/data/images/<?= htmlspecialchars($c['image_chapitre']) ?>" alt="Image chapitre" style="max-width:80px;max-height:80px;">
                        <?php endif; ?>
                    </td>
                    <td><?= $c['ordre_chapitre'] ?></td>
                    <td>
                        <a href="edit_chapitre.php?id=<?= $c['id_chapitre'] ?>">Modifier</a>
                        <a href="delete_chapitre.php?id=<?= $c['id_chapitre'] ?>&id_etape=<?= $id_etape ?>" onclick="return confirm('Supprimer ce chapitre ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="edit_etape.php?id=<?= $id_etape ?>">Retour à l'étape</a>
    <a href="list_etapes.php?id_parcours=<?= $id_parcours ?>" style="margin-left:20px;">Retour à la liste des étapes du parcours</a>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>

</body>

</html>