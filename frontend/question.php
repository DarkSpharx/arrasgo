<?php
// Page question entre deux étapes
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../backend/functions/etapes.php';

$id_parcours = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_etape = isset($_GET['etape']) ? intval($_GET['etape']) : 0;
$geo = isset($_GET['geo']) ? intval($_GET['geo']) : 0;

$etape = $id_etape ? get_etape($pdo, $id_etape) : null;
if (!$etape || empty($etape['question_etape'])) {
    http_response_code(404);
    header('Location: /frontend/error404.php');
    exit;
}
// Validation de la réponse
$reponse_validee = false;
$bonne_reponse = $etape['reponse_attendue'] ?? '';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reponse'])) {
    $reponse = trim(mb_strtolower($_POST['reponse']));
    $bonne = trim(mb_strtolower($bonne_reponse));
    if ($bonne && $reponse === $bonne) {
        // Recherche de l'étape suivante
        require_once __DIR__ . '/../backend/functions/parcours.php';
        $etapes = get_etapes_by_parcours($pdo, $id_parcours);
        $etape_suivante = null;
        for ($i = 0; $i < count($etapes); $i++) {
            if ($etapes[$i]['id_etape'] == $id_etape && isset($etapes[$i + 1])) {
                $etape_suivante = $etapes[$i + 1];
                break;
            }
        }
        if ($etape_suivante) {
            header('Location: etape.php?id=' . $id_parcours . '&etape=' . $etape_suivante['id_etape'] . '&geo=' . $geo);
            exit;
        } else {
            $reponse_validee = true;
            $message = '<div class="alert-success">Bonne réponse !</div>';
        }
    } else {
        $message = '<div class="alert-error">Mauvaise réponse, essayez encore.</div>';
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
    <link rel="stylesheet" href="css/ficheEtape.css">
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
    <title>Question étape <?= $id_etape ?> - Arras Go</title>
    <style>

    </style>
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
    <main>
        <section>
            <div class="heroNohome">
                <?php
                // Numéro de l'étape dans le parcours
                require_once __DIR__ . '/../backend/functions/parcours.php';
                $etapes = get_etapes_by_parcours($pdo, $id_parcours);
                $numero_etape = 1;
                for ($i = 0; $i < count($etapes); $i++) {
                    if ($etapes[$i]['id_etape'] == $id_etape) {
                        $numero_etape = $i + 1;
                        break;
                    }
                }
                ?>
                <h1>Étape <?= $numero_etape ?> : <?= htmlspecialchars($etape['titre_etape']) ?></h1>
            </div>
        </section>
        <section class="question">
            <div class="question-card">
                <div class="card-indice">
                    <button type="button" class="btn" id="show-indice-image">
                        Indice visuel
                    </button>
                    <button type="button" class="btn" id="show-indice-texte">
                        Indice textuel
                    </button>
                </div>
                <?php if (!$reponse_validee): ?>
                    <?= $message ?>
                    <?php if (!$reponse_validee): ?>
                        <?php if ($geo == 1 && !empty($etape['lat']) && !empty($etape['lng'])): ?>
                            <div id="geo-message" style="margin-bottom:0.8rem;"></div>
                            <div id="geo-loader" style="margin-bottom:0.8rem; display:none;">Recherche de votre position...</div>
                            <div id="geo-error" style="color:red; margin-bottom:0.8rem;"></div>
                        <?php endif; ?>
                        <form class="question-form" id="etape-form" method="post" action="?id=<?= $id_parcours ?>&etape=<?= $id_etape ?>&geo=<?= $geo ?>">
                            <div class="etape-question-txt"><?= htmlspecialchars($etape['question_etape']) ?></div>
                            <br>
                            <input type="text" name="reponse" placeholder="Votre réponse..." required>
                            <br>
                            <a href="#" class="btn" role="button" tabindex="0" onclick="document.getElementById('etape-form').submit(); return false;">Valider</a>
                        </form>
                    <?php else: ?>
                        <?= $message ?>
                        <?php
                        // Recherche de l'étape suivante
                        require_once __DIR__ . '/../backend/functions/parcours.php';
                        $etapes = get_etapes_by_parcours($pdo, $id_parcours);
                        $etape_suivante = null;
                        for ($i = 0; $i < count($etapes); $i++) {
                            if ($etapes[$i]['id_etape'] == $id_etape && isset($etapes[$i + 1])) {
                                $etape_suivante = $etapes[$i + 1];
                                break;
                            }
                        }
                        $is_last = !$etape_suivante;
                        echo '<div class="cta-group" style="margin-top:1.5rem;flex-wrap:wrap;gap:1rem;">';
                        if ($is_last) {
                            echo '<a href="parcours.php" class="btn">Choisir un autre parcours</a>';
                            echo '<a href="index.php" class="btn">Accueil</a>';
                        } else {
                            echo '<a href="etape.php?id=' . $id_parcours . '&etape=' . $etape_suivante['id_etape'] . '&geo=' . $geo . '" class="btn">Étape suivante</a>';
                        }
                        echo '</div>';
                        ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <div id="popup-indice-texte" class="popup-overlay" style="display:none;">
        <div class="popup-content">
            <button class="popup-close" onclick="closePopup('popup-indice-texte')">&times;</button>
            <div class="popup-indice-texte">
                <?= !empty($etape['indice_etape_texte']) ? nl2br(htmlspecialchars($etape['indice_etape_texte'])) : '<span style="color:#888">Aucun indice textuel disponible.</span>' ?>
            </div>
        </div>
    </div>
    <div id="popup-indice-image" class="popup-overlay" style="display:none;">
        <div class="popup-content" style="position:relative;">
            <button class="popup-close" onclick="closePopup('popup-indice-image')">&times;</button>
            <?php if (!empty($etape['indice_etape_image'])): ?>
                <div style="position:relative;">
                    <img id="popup-img-indice" src="../data/images/<?= htmlspecialchars($etape['indice_etape_image']) ?>" alt="Indice visuel" class="popup-img" style="transition: transform 0.2s;">
                    <div class="zoom-controls">
                        <button type="button" id="zoom-out" class="zoom-btn">-</button>
                        <button type="button" id="zoom-in" class="zoom-btn">+</button>
                    </div>
                </div>
            <?php else: ?>
                <span style="color:#888">Aucune image d'indice disponible.</span>
            <?php endif; ?>
        </div>
    </div>
    <?php include __DIR__ . '/footer.php'; ?>

    <script src="js/script.js"></script>
    <script>
        function closePopup(id) {
            document.getElementById(id).style.display = 'none';
        }
        document.getElementById('show-indice-texte')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('popup-indice-texte').style.display = 'flex';
        });
        document.getElementById('show-indice-image')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('popup-indice-image').style.display = 'flex';
            // Reset zoom on open
            const img = document.getElementById('popup-img-indice');
            if (img) img.style.transform = 'scale(1)';
        });

        // Zoom controls
        let zoomLevel = 1;
        document.getElementById('zoom-in')?.addEventListener('click', function() {
            const img = document.getElementById('popup-img-indice');
            if (img && zoomLevel < 3) {
                zoomLevel += 0.2;
                img.style.transform = `scale(${zoomLevel})`;
            }
        });
        document.getElementById('zoom-out')?.addEventListener('click', function() {
            const img = document.getElementById('popup-img-indice');
            if (img && zoomLevel > 0.4) {
                zoomLevel -= 0.2;
                img.style.transform = `scale(${zoomLevel})`;
            }
        });

        <?php if ($geo == 1 && !empty($etape['lat']) && !empty($etape['lng'])): ?>
            // Géolocalisation : bloque la validation si hors zone (25m)
            const rayon = 25;
            const etapeLat = <?= floatval($etape['lat']) ?>;
            const etapeLng = <?= floatval($etape['lng']) ?>;
            const geoMessage = document.getElementById('geo-message');
            const geoLoader = document.getElementById('geo-loader');
            const geoError = document.getElementById('geo-error');
            const form = document.getElementById('etape-form');

            function distanceGPS(lat1, lng1, lat2, lng2) {
                const R = 6371e3;
                const toRad = x => x * Math.PI / 180;
                const dLat = toRad(lat2 - lat1);
                const dLng = toRad(lng2 - lng1);
                const a = Math.sin(dLat / 2) ** 2 + Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLng / 2) ** 2;
                return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            }

            function checkPosition() {
                geoLoader.style.display = 'block';
                geoError.textContent = '';
                navigator.geolocation.getCurrentPosition(function(pos) {
                    geoLoader.style.display = 'none';
                    const d = distanceGPS(pos.coords.latitude, pos.coords.longitude, etapeLat, etapeLng);
                    if (d <= rayon) {
                        geoMessage.innerHTML = '<span style="color:green">Vous êtes sur le lieu, la question est débloquée.</span>';
                        form.querySelector('input[type="text"]').disabled = false;
                        form.querySelector('button[type="submit"]').disabled = false;
                    } else {
                        geoMessage.innerHTML = '<span style="color:orange">Vous êtes à ' + Math.round(d) + ' m du lieu. Rendez-vous sur place pour débloquer la question.</span>';
                        form.querySelector('input[type="text"]').disabled = true;
                        form.querySelector('button[type="submit"]').disabled = true;
                    }
                }, function(err) {
                    geoLoader.style.display = 'none';
                    geoError.textContent = 'Erreur de géolocalisation : ' + err.message;
                    form.querySelector('input[type="text"]').disabled = true;
                    form.querySelector('button[type="submit"]').disabled = true;
                });
            }
            // Initialisation
            form.querySelector('input[type="text"]').disabled = true;
            form.querySelector('button[type="submit"]').disabled = true;
            checkPosition();
        <?php endif; ?>
    </script>
</body>

</html>