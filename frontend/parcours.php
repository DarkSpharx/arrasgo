<?php
// Connexion à la BDD
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../backend/functions/parcours.php';

// Récupération des parcours
if (function_exists('readParcours')) {
    $parcours = readParcours($pdo);
} elseif (function_exists('get_all_parcours')) {
    $parcours = get_all_parcours($pdo);
} else {
    $stmt = $pdo->query('SELECT * FROM parcours WHERE statut = 1 ORDER BY id_parcours DESC');
    $parcours = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parcours - Arras Go</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header class="main-header">
        <div class="container">
            <h1>Arras Go</h1>
            <button id="menu-toggle" aria-label="Ouvrir le menu">☰</button>
        </div>
        <nav id="main-nav" class="main-nav">
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="parcours.php" class="active">Parcours</a></li>
                <li><a href="personnages.php">Personnages</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="parcours-list">
            <h2>Liste des parcours</h2>
            <div class="parcours-cards">
                <?php if (!empty($parcours)): ?>
                    <?php foreach ($parcours as $p): ?>
                        <article class="parcours-card">
                            <?php if (!empty($p['image_parcours'])): ?>
                                <img src="../data/images/<?= htmlspecialchars($p['image_parcours']) ?>" alt="Image du parcours" class="parcours-img">
                            <?php else: ?>
                                <div class="parcours-img placeholder"></div>
                            <?php endif; ?>
                            <div class="parcours-content">
                                <h3><?= htmlspecialchars($p['nom_parcours'] ?? $p['name'] ?? 'Parcours') ?></h3>
                                <p><?= htmlspecialchars($p['description_parcours'] ?? $p['description'] ?? '') ?></p>
                                <a href="parcours_detail.php?id=<?= urlencode($p['id_parcours']) ?>" class="btn">Voir le parcours</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun parcours disponible pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Arras Go. Tous droits réservés.</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>

</html>