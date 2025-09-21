<?php
// Détail d'un personnage
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../backend/functions/personnages.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$personnage = $id ? get_personnage($pdo, $id) : null;

if (!$personnage) {
    http_response_code(404);
    header('Location: /frontend/error404.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Musée des Beaux-Arts d’Arras">
    <meta name="description" content="Arras Go est un jeu gratuit et immersif en centre-ville d’Arras. Vivez une enquête autour de l’histoire du théâtre d’Arras !" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/fichePerso.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta
        property="og:title"
        content="Arras Go - Jeu piéton gratuit en centre-ville d'Arras" />
    <meta
        property="og:description"
        content="Une expérience ludique et immersive autour de l’histoire et du patrimoine d’Arras" />
    <meta property="og:image" content="media/image/bg_pc.webp" />
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <title>Arras Go - Détail de <?= htmlspecialchars($personnage['nom_personnage']) ?></title>
</head>

<body>
    <header>
        <div>
            <a href="index.php">
                <img src="./media/logo/logo_long_monochrome_white.svg" alt="Arras Go Logo">
            </a>
            <button id="menu-toggle" aria-label="Ouvrir le menu"><i class="fa-solid fa-bars"></i></button>
            <nav id="main-nav" class="main-nav">
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="parcours.php">Parcours</a></li>
                    <li><a href="personnages.php">Personnages</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <section class="heroNohome">
            <h1><?= htmlspecialchars($personnage['nom_personnage']) ?></h1>
        </section>
        <section class="detail-personnage" style="position:relative;">

            <div class="detail-card" style="position:relative;z-index:1;">
                <?php if (!empty($personnage['image_personnage'])): ?>
                    <div class="detail-img-container">
                        <div class="detail-img-bg" style="background:url('../data/images/<?= htmlspecialchars($personnage['image_personnage']) ?>') center/cover no-repeat;"></div>
                        <img src="../data/images/<?= htmlspecialchars($personnage['image_personnage']) ?>" alt="Portrait de <?= htmlspecialchars($personnage['nom_personnage']) ?>" class="detail-img">
                    </div>
                <?php endif; ?>
                <div class="detail-content">
                    <p><?= nl2br(htmlspecialchars($personnage['description_personnage'])) ?></p>
                    <a href="personnages.php" class="btn">← Retour à la liste</a>
                </div>
            </div>
        </section>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>