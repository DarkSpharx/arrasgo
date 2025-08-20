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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style_backoffice.css">
    <link rel="stylesheet" href="css/header_footer.css">
    <link rel="stylesheet" href="css/tab.css">
    <script src="js/admin.js" defer></script>
    <title>Chapitres de l'étape</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <h1 class="h1-sticky">Chapitres de l'étape</h1>
    <main>
        <div class="cards-container">
            <a href="add_chapitre.php?id_etape=<?= $id_etape ?>" class="button" style="margin-bottom:16px;">Ajouter un chapitre</a>
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
                            <td data-label="ID">#<?= $c['id_chapitre'] ?></td>
                            <td data-label="Titre"><?= htmlspecialchars($c['titre_chapitre']) ?></td>
                            <td data-label="Texte"><?= htmlspecialchars($c['texte_chapitre']) ?></td>
                            <td data-label="Image">
                                <?php if (!empty($c['image_chapitre'])): ?>
                                    <img src="/data/images/<?= htmlspecialchars($c['image_chapitre']) ?>" alt="Image chapitre" class="tab-indice-img" />
                                <?php endif; ?>
                            </td>
                            <td data-label="Ordre"><?= $c['ordre_chapitre'] ?></td>
                            <td data-label="Actions">
                                <div class="tab-actions">
                                    <a href="edit_chapitre.php?id=<?= $c['id_chapitre'] ?>" class="button-tab">Modifier</a>
                                    <a href="delete_chapitre.php?id=<?= $c['id_chapitre'] ?>&id_etape=<?= $id_etape ?>" class="button-tab delete-parcours" onclick="return confirm('Supprimer ce chapitre ?');">Supprimer</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="liens-container">
                <a class="button" href="list_etapes.php?id_parcours=<?= $id_parcours ?>" class="liens">Retour à la liste des étapes du parcours</a>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
</body>

</html>