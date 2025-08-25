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
            <div class="cards-grid">
                <div class="card-button">
                    <a href="add_personnage.php" class="button">+ Ajouter une personnalité</a>
                    <a href="dashboard.php" class="button-tab">🔙 Retour au Dashboard </a>
                </div>

                <?php foreach ($personnages as $p): ?>
                    <div class="card">
                        <h2><?= htmlspecialchars($p['nom_personnage']) ?></h2>

                        <strong>Illustration de la personnalité</strong>

                        <div class="card-img">
                            <?php if (!empty($p['image_personnage'])): ?>
                                <img src="../data/images/<?= htmlspecialchars($p['image_personnage']) ?>" alt="Image personnage" class="tab-indice-img" />
                            <?php endif; ?>
                        </div>

                        <strong>Description de la personnalité</strong>

                        <div><?= htmlspecialchars($p['description_personnage']) ?>
                        </div>

                        <strong>Parcours liés</strong>

                        <div>
                            <?php if (!empty($personnages_parcours[$p['id_personnage']])): ?>
                                <?= htmlspecialchars(implode(', ', $personnages_parcours[$p['id_personnage']])) ?>
                            <?php else: ?>
                                <em>Aucun</em>
                            <?php endif; ?>
                        </div>

                        <div class="card-actions">
                            <a href="edit_personnage.php?id=<?= $p['id_personnage'] ?>" class="button-tab">Modifier</a>
                            <a href="delete_personnage.php?id=<?= $p['id_personnage'] ?>" class="button-tab delete-parcours" onclick="return confirm('Supprimer ce personnage ?');">Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
</body>

</html>