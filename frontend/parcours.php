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
    if (is_object($pdo)) {
        $stmt = $pdo->query('SELECT * FROM parcours WHERE statut = 1 ORDER BY id_parcours DESC');
        $parcours = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    } else {
        $parcours = [];
    }
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

    <title>Arras Go - Les Parcours</title>
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
        <section class="heroNohome">
            <h1>Le(s) parcours</h1>
        </section>
        <section class="container e7d8c9 minheight-100vh">
            <div class="parcours-cards">
                <?php if (!empty($parcours)): ?>
                    <?php foreach ($parcours as $p): ?>
                        <article class="parcours-card">
                            <a href="parcours_detail.php?id=<?= urlencode($p['id_parcours']) ?>" class="parcours-card-link" tabindex="-1" aria-hidden="true" style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:1;"></a>
                            <?php if (!empty($p['image_parcours'])): ?>
                                <img src="../data/images/<?= htmlspecialchars($p['image_parcours']) ?>" alt="Image du parcours" class="parcours-img">
                            <?php else: ?>
                                <div class="parcours-img placeholder"></div>
                            <?php endif; ?>
                            <div class="parcours-content" style="position:relative;z-index:2;">
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
        <section class="container fff">
            <div class="corps">
                <h2>2 modes de jeu</h2>
                <div class="two-column">
                    <div class="column">
                        <h3>Le Mode explorateur
                            <br>
                            <p>(avec géolocalisation)</p>
                        </h3>
                        <p>Partez dans les rues d'Arras avec votre smartphone et vivez l'enquête en temps réel au cœur de la ville.</p>
                    </div>
                    <div class="column">
                        <h3>Le Mode découverte
                            <br>
                            <p>(sans géolocalisation)</p>
                        </h3>
                        <p>Explorez les énigmes depuis un ordinateur, chez vous ou en classe, sans vous déplacer physiquement.</p>
                    </div>
                </div>
                <hr>
                <em>Chaque étape du jeu est accompagnée d’un lecteur audio avec une retranscription audio du texte afin de faciliter la jouabilité de manière plus immersive.</em>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>