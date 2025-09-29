<?php
// Page étape unique pour les deux modes (avec/sans géolocalisation)
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../backend/functions/etapes.php';
require_once __DIR__ . '/../backend/functions/parcours.php';

$id_parcours = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_etape = isset($_GET['etape']) ? intval($_GET['etape']) : 0;
$geo = isset($_GET['geo']) ? intval($_GET['geo']) : 0;

// Récupération du parcours (protégé)
$parcours = null;
if ($id_parcours && is_object($pdo) && function_exists('get_parcours')) {
    $parcours = get_parcours($pdo, $id_parcours);
} elseif ($id_parcours && is_object($pdo)) {
    $stmt = $pdo->prepare('SELECT * FROM parcours WHERE id_parcours = ?');
    $stmt->execute([$id_parcours]);
    $parcours = $stmt->fetch(PDO::FETCH_ASSOC);
}
// Récupération de l'étape (protégé)
$etape = null;
if ($id_etape && is_object($pdo) && function_exists('get_etape')) {
    $etape = get_etape($pdo, $id_etape);
}
if (!$etape) {
    http_response_code(404);
    header('Location: /frontend/error404.php');
    exit;
}
// Pour la navigation entre étapes (protégé)
$etapes = [];
if (is_object($pdo) && function_exists('get_etapes_by_parcours')) {
    $etapes = get_etapes_by_parcours($pdo, $id_parcours);
}

// Calcul de la distance (JS côté client)
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
    <title>Arras Go - Étape <?= htmlspecialchars($etape['titre_etape']) ?> du parcours <?= htmlspecialchars($parcours['nom_parcours']) ?></title>
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

    <?php if (!empty($etape['mp3_etape'])): ?>
        <div class="audio-header sticky-audio">
            <audio controls src="../data/mp3/<?= htmlspecialchars($etape['mp3_etape']) ?>" style="width:100%"></audio>
        </div>
    <?php endif; ?>

    <main>
        <section class="heroNohome">
            <?php
            // Recherche du numéro d'étape dans le parcours
            $numero_etape = 1;
            foreach ($etapes as $idx => $e) {
                if ($e['id_etape'] == $id_etape) {
                    $numero_etape = $idx + 1;
                    break;
                }
            }
            ?>
            <h1>Étape <?= $numero_etape ?> : <?= htmlspecialchars($etape['titre_etape']) ?></h1>
        </section>
        <section class="etape">
            <div class="detail-etape">
                <?php
                require_once __DIR__ . '/../backend/functions/chapitres.php';
                $chapitres = get_chapitres_by_etape($pdo, $id_etape);
                $titre_chapitre = '';
                if (!empty($chapitres) && !empty($chapitres[0]['titre_chapitre'])) {
                    $titre_chapitre = $chapitres[0]['titre_chapitre'];
                }
                if (!empty($chapitres)) {
                    echo '<div class="chapitres-list">';
                    foreach ($chapitres as $chapitre) {
                        echo '<div class="etape-chapitre-grid">';
                        // Colonne image
                        echo '<div class="etape-chapitre-img">';
                        if (!empty($chapitre['image_chapitre'])) {
                            echo '<img src="../data/images/' . htmlspecialchars($chapitre['image_chapitre']) . '" alt="Image du chapitre">';
                        }
                        echo '</div>';
                        // Colonne texte
                        echo '<div class="etape-chapitre-txt">';
                        if (!empty($chapitre['titre_chapitre'])) {
                            echo '<h2>' . htmlspecialchars($chapitre['titre_chapitre']) . '</h2><br>';
                        }
                        if (!empty($chapitre['texte_chapitre'])) {
                            $texte = $chapitre['texte_chapitre'];
                            $texte = preg_replace_callback(
                                '/<iframe[^>]*src="https?:\/\/www\.youtube\.com\/embed\/[^"<>]+"[^>]*><\/iframe>/i',
                                function ($matches) {
                                    return $matches[0];
                                },
                                $texte
                            );
                            $texte = strip_tags($texte, '<iframe>');
                            echo '<div class="chapitre-texte">' . nl2br($texte) . '</div>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                }

                // Navigation/question : bouton selon présence de question ou fin de parcours
                // Recherche de l'étape suivante
                $etapes = get_etapes_by_parcours($pdo, $id_parcours);
                $etape_suivante = null;
                for ($i = 0; $i < count($etapes); $i++) {
                    if ($etapes[$i]['id_etape'] == $id_etape && isset($etapes[$i + 1])) {
                        $etape_suivante = $etapes[$i + 1];
                        break;
                    }
                }
                $is_last = !$etape_suivante;

                echo '<div class="cta-group end-success" style="margin-top:2rem;">';
                if ($is_last) {
                    echo '<div class="alert-success">Félicitations, vous avez terminé le parcours !</div>';
                    echo '<a href="parcours.php" class="btn">Choisir un autre parcours</a>';
                    echo '<a href="index.php" class="btn">Accueil</a>';
                    echo '<a href="personnages.php" class="btn">Personnages</a>';
                } else if (!empty($etape['question_etape'])) {
                    // Vérifie qu’on n’est pas déjà sur la page question (évite boucle)
                    $currentScript = basename($_SERVER['PHP_SELF']);
                    if ($currentScript !== 'question.php') {
                        echo '<a href="question.php?id=' . $id_parcours . '&etape=' . $id_etape . '&geo=' . $geo . '" class="btn">Répondre à la question</a>';
                    }
                } else if ($etape_suivante) {
                    echo '<a href="etape.php?id=' . $id_parcours . '&etape=' . $etape_suivante['id_etape'] . '&geo=' . $geo . '" class="btn">Étape suivante</a>';
                }
                echo '</div>';
                ?>
            </div>
        </section>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>

    <?php if ($geo == 1 && !empty($etape['lat']) && !empty($etape['lng'])): ?>
        <script>
            // JS géolocalisation : bloquer la question si l'utilisateur n'est pas dans la zone
            const rayon = 50; // mètres
            const etapeLat = <?= floatval($etape['lat']) ?>;
            const etapeLng = <?= floatval($etape['lng']) ?>;
            const geoMessage = document.getElementById('geo-message');
            const geoLoader = document.getElementById('geo-loader');
            const geoError = document.getElementById('geo-error');
            const form = document.getElementById('etape-form');
            let debloque = false;

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
                        form.style.display = '';
                        debloque = true;
                    } else {
                        geoMessage.innerHTML = '<span style="color:orange">Vous êtes à ' + Math.round(d) + ' m du lieu. Rendez-vous sur place pour débloquer la question.</span>';
                        form.style.display = 'none';
                        debloque = false;
                    }
                }, function(err) {
                    geoLoader.style.display = 'none';
                    geoError.textContent = 'Erreur de géolocalisation : ' + err.message;
                    form.style.display = 'none';
                });
            }
            form.style.display = 'none';
            checkPosition();
        </script>
    <?php elseif ($geo == 1): ?>
        <script>
            document.getElementById('geo-message').innerHTML = '<span style="color:red">Géolocalisation non disponible pour cette étape.</span>';
            document.getElementById('etape-form').style.display = 'none';
        </script>
    <?php endif; ?>
    <?php include __DIR__ . '/footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>