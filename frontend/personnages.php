<?php
// Connexion à la BDD
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../backend/functions/personnages.php';

// Récupération des personnages
$personnages = get_all_personnages($pdo);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personnages - Arras Go</title>
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
                <li><a href="parcours.php">Parcours</a></li>
                <li><a href="personnages.php" class="active">Personnages</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="parcours-list">
            <h2>Personnages</h2>
            <div class="parcours-cards">
                <?php if (!empty($personnages)): ?>
                    <?php foreach ($personnages as $perso): ?>
                        <article class="parcours-card">
                            <?php if (!empty($perso['image_personnage'])): ?>
                                <img src="../data/images/<?= htmlspecialchars($perso['image_personnage']) ?>" alt="Portrait de <?= htmlspecialchars($perso['nom_personnage']) ?>" class="parcours-img">
                            <?php else: ?>
                                <div class="parcours-img placeholder"></div>
                            <?php endif; ?>
                            <div class="parcours-content">
                                <h3><?= htmlspecialchars($perso['nom_personnage']) ?></h3>
                                <a href="personnage.php?id=<?= urlencode($perso['id_personnage']) ?>" class="btn">Voir la personnalité</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun personnage enregistré pour le moment.</p>
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