<?php
http_response_code(404);
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

    <title>Page introuvable - 404</title>
</head>

<body>
    <header>
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
                </ul>
            </nav>
        </div>
    </header>

    <main class="main">
        <section class="hero">
            <div class="down">
                <img src="./media/logo/pin.svg" class="float" alt="Icone de localisation">
            </div>
            <img src="./media/logo/cercle.svg" alt="Cercle décoratif">
            <h1><i class="fa-solid fa-triangle-exclamation"></i>404 - Page introuvable</h1>
            <h2>Oups, la page demandée n'existe pas ou a été déplacée.</h2>
            <a href="index.php" class="btn cta">Retour à l'accueil</a>
        </section>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>