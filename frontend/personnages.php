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
    <meta
        name="description"
        content="Arras Go est un jeu gratuit et immersif en centre-ville d’Arras. Vivez une enquête autour de l’histoire du théâtre d’Arras !" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta
        property="og:title"
        content="Arras Go - Jeu piéton gratuit en centre-ville d'Arras" />
    <meta
        property="og:description"
        content="Une expérience ludique et immersive autour de l’histoire et du patrimoine d’Arras" />
    <meta property="og:image" content="assets/photos_webp/photo_accueil.webp" />
    <meta property="og:url" content="https://arras-go.fr/" />
    <meta property="og:type" content="website" />
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="media/favicon/favicon.svg" />
    <link rel="icon" type="image/png" href="media/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="shortcut icon" href="media/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="media/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Arras Go" />
    <link rel="manifest" href="media/favicon/site.webmanifest" />
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/photos_webp/photo_accueil.webp" as="image">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <title>Arras Go - Les Personnalités</title>
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
        <section class="container e7d8c9 minheight-100vh">
            <h1>Le(s) personnalités</h1>
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