<?php
// Page étape unique pour les deux modes (avec/sans géolocalisation)
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../backend/functions/etapes.php';
require_once __DIR__ . '/../backend/functions/parcours.php';

$id_parcours = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_etape = isset($_GET['etape']) ? intval($_GET['etape']) : 0;
$geo = isset($_GET['geo']) ? intval($_GET['geo']) : 0;

$parcours = $id_parcours ? (function_exists('get_parcours') ? get_parcours($pdo, $id_parcours) : null) : null;
if (!$parcours) {
    $stmt = $pdo->prepare('SELECT * FROM parcours WHERE id_parcours = ?');
    $stmt->execute([$id_parcours]);
    $parcours = $stmt->fetch(PDO::FETCH_ASSOC);
}
$etape = $id_etape ? get_etape($pdo, $id_etape) : null;
if (!$etape) {
    http_response_code(404);
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Étape introuvable</title></head><body><h1>Étape introuvable</h1><p>L\'étape demandée n\'existe pas.</p></body></html>';
    exit;
}
// Pour la navigation entre étapes
$etapes = get_etapes_by_parcours($pdo, $id_parcours);

// Calcul de la distance (JS côté client)
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($etape['titre_etape']) ?> - Étape</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="cgu.php">CGU</a></li>
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
        <section class="etape-question">
            <div class="etape-card">

                <h3>Étape : <?= htmlspecialchars($etape['titre_etape']) ?></h3>
                <?php
                // Affichage des chapitres (titre, texte, image)
                require_once __DIR__ . '/../backend/functions/chapitres.php';
                $chapitres = get_chapitres_by_etape($pdo, $id_etape);
                if (!empty($chapitres)) {
                    echo '<div class="chapitres-list">';
                    foreach ($chapitres as $chapitre) {
                        echo '<div class="etape-chapitre" style="margin-bottom:1.2rem;">';
                        if (!empty($chapitre['titre_chapitre'])) {
                            echo '<strong>' . htmlspecialchars($chapitre['titre_chapitre']) . '</strong><br>';
                        }
                        if (!empty($chapitre['image_chapitre'])) {
                            echo '<img src="../data/images/' . htmlspecialchars($chapitre['image_chapitre']) . '" alt="Image du chapitre" style="max-width:220px;max-height:140px;display:block;margin:0.5rem auto;">';
                        }
                        if (!empty($chapitre['texte_chapitre'])) {
                            echo '<div class="chapitre-texte">' . nl2br(htmlspecialchars($chapitre['texte_chapitre'])) . '</div>';
                        }
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

                echo '<div class="cta-group" style="margin-top:2rem;flex-wrap:wrap;gap:1rem;">';
                if ($is_last) {
                    echo '<div class="alert-success">Félicitations, vous avez terminé le parcours !</div>';
                    echo '<a href="parcours.php" class="btn">Choisir un autre parcours</a>';
                    echo '<a href="index.php" class="btn">Accueil</a>';
                    echo '<a href="personnages.php" class="btn">Personnages</a>';
                    echo '<a href="contact.php" class="btn">Contact</a>';
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
    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Arras Go. Tous droits réservés.</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
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
</body>

</html>