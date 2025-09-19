<?php
// Connexion à la BDD
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../backend/functions/parcours.php';

// Récupération des parcours
if (function_exists('readParcours')) {
    $parcours = array_filter(readParcours($pdo), function ($p) {
        return isset($p['statut']) ? $p['statut'] == 1 : true;
    });
} elseif (function_exists('get_all_parcours')) {
    $parcours = array_filter(get_all_parcours($pdo), function ($p) {
        return isset($p['statut']) ? $p['statut'] == 1 : true;
    });
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
    <title>Arras Go - Découverte des parcours</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header class="headerall">
        <div>
            <a href="index.php">
                <img src="./media/logo/logo_long_monochrome_white.svg" alt="Arras Go Logo" class="left">
            </a>
            <button id="menu-toggle" aria-label="Ouvrir le menu" class="right"><i class="fa-solid fa-bars"></i></button>
            <nav id="main-nav" class="main-nav">
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="parcours.php">Parcours</a></li>
                    <li><a href="personnages.php">Personnages</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="cgu.php">CGU</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main">
        <section class="container fff">
            <h1>Le(s) parcours</h1>
        </section>
        <section class="container e7d8c9 minheight-100vh">
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