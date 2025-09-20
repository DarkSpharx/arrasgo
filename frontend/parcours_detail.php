<?php
// Détail d'un parcours
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../backend/functions/parcours.php';
require_once __DIR__ . '/../backend/functions/etapes.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$parcours = $id ? (function_exists('get_parcours') ? get_parcours($pdo, $id) : null) : null;
if (!$parcours) {
    // fallback direct
    $stmt = $pdo->prepare('SELECT * FROM parcours WHERE id_parcours = ?');
    $stmt->execute([$id]);
    $parcours = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (!$parcours) {
    http_response_code(404);
    header('Location: /frontend/error404.php');
    exit;
}
// Récupérer la première étape du parcours
$etapes = get_etapes_by_parcours($pdo, $id);
$premiere_etape_id = !empty($etapes) ? $etapes[0]['id_etape'] : null;
?>
<!DOCTYPE html>
<html lang="fr">

<head>

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
        <title>Arras Go - Détail du parcours <?= htmlspecialchars($parcours['nom_parcours']) ?></title>
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
            <h1><?= htmlspecialchars($parcours['nom_parcours']) ?></h1>
        </section>
        <section class="detail-parcours">
            <div class="detail-card">
                <?php if (!empty($parcours['image_parcours'])): ?>
                    <img src="../data/images/<?= htmlspecialchars($parcours['image_parcours']) ?>" alt="Image du parcours" class="detail-img">
                <?php endif; ?>
                <div class="detail-content">
                    <h2><?= htmlspecialchars($parcours['nom_parcours']) ?></h2>
                    <p><?= nl2br(htmlspecialchars($parcours['description_parcours'])) ?></p>
                    <?php if ($premiere_etape_id): ?>
                        <div class="cta-group" style="margin-top:2rem;">
                            <a href="etape.php?id=<?= $id ?>&etape=<?= $premiere_etape_id ?>&geo=1" class="btn cta">Démarrer avec géolocalisation</a>
                            <a href="etape.php?id=<?= $id ?>&etape=<?= $premiere_etape_id ?>&geo=0" class="btn cta">Démarrer sans géolocalisation</a>
                        </div>
                    <?php else: ?>
                        <p>Aucune étape disponible pour ce parcours.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>