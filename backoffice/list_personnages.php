<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
require_once '../backend/functions/personnages.php';

// Récupérer tous les personnages
$personnages = get_all_personnages($pdo);

// Récupérer tous les parcours
$parcours = [];
$stmt = $pdo->query("SELECT id_parcours, nom_parcours FROM parcours");
if ($stmt) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $parcours[$row['id_parcours']] = $row['nom_parcours'];
    }
}

// Récupérer les parcours liés à chaque personnage
$personnages_parcours = [];
foreach ($personnages as $perso) {
    $stmt = $pdo->prepare("SELECT id_parcours FROM parcours_personnages WHERE id_personnage = ?");
    $stmt->execute([$perso['id_personnage']]);
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $noms = array_map(function ($id) use ($parcours) {
        return $parcours[$id] ?? '';
    }, $ids);
    $personnages_parcours[$perso['id_personnage']] = $noms;
}
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
    <title>Liste des Personnalités</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <h1 class="h1-sticky">Personnalités de ARRAS GO</h1>
    <main>
        <div class="cards-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Parcours liés</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($personnages as $p): ?>
                        <tr>
                            <td data-label="ID">#<?= $p['id_personnage'] ?></td>
                            <td data-label="Nom"><?= htmlspecialchars($p['nom_personnage']) ?></td>
                            <td data-label="Description"><?= htmlspecialchars($p['description_personnage']) ?></td>
                            <td data-label="Image">
                                <?php if (!empty($p['image_personnage'])): ?>
                                    <img src="../data/images/<?= htmlspecialchars($p['image_personnage']) ?>" alt="Image personnage" class="tab-indice-img" />
                                <?php endif; ?>
                            </td>
                            <td data-label="Parcours liés">
                                <?php if (!empty($personnages_parcours[$p['id_personnage']])): ?>
                                    <?= htmlspecialchars(implode(', ', $personnages_parcours[$p['id_personnage']])) ?>
                                <?php else: ?>
                                    <em>Aucun</em>
                                <?php endif; ?>
                            </td>
                            <td data-label="Actions">
                                <div class="tab-actions">
                                    <a href="edit_personnage.php?id=<?= $p['id_personnage'] ?>" class="button-tab">Modifier</a>
                                    <a href="delete_personnage.php?id=<?= $p['id_personnage'] ?>" class="button-tab delete-parcours" onclick="return confirm('Supprimer ce personnage ?');">Supprimer</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="add_personnage.php" class="button" style="margin-bottom:16px;">Ajouter un personnage</a>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
</body>

</html>