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
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Question introuvable</title></head><body><h1>Question introuvable</h1><p>Aucune question pour cette étape.</p></body></html>';
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
        $reponse_validee = true;
        $message = '<div class="alert-success">Bonne réponse !</div>';
    } else {
        $message = '<div class="alert-error">Mauvaise réponse, essayez encore.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question - Arras Go</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
    .popup-overlay {
        position: fixed; left: 0; top: 0; width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;
    }
    .popup-content {
        background: #fff; border-radius: 10px; max-width: 90vw; max-height: 80vh; padding: 1.5rem; position: relative;
        box-shadow: 0 4px 24px rgba(0,0,0,0.18);
        overflow: auto;
    }
    .popup-close {
        position: absolute; top: 8px; right: 12px; font-size: 2rem; color: #333; background: none; border: none; cursor: pointer;
    }
    .popup-img { max-width: 80vw; max-height: 60vh; display: block; margin: 0 auto; }
    .popup-indice-texte { font-size: 1.1rem; color: #222; }
    .question-indices { display: flex; gap: 1.5rem; margin-bottom: 1.2rem; flex-wrap: wrap; }
    .question-indices a { color: #1d1d1b; text-decoration: underline; cursor: pointer; font-weight: 500; }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="container" style="background: rgba(29,29,27,0.85); border-radius: 0 0 12px 12px;">
            <h1>Arras Go</h1>
            <button id="menu-toggle" aria-label="Ouvrir le menu">☰</button>
        </div>
        <nav id="main-nav" class="main-nav">
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="parcours.php">Parcours</a></li>
                <li><a href="personnages.php">Personnages</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="question-section">
            <div class="etape-card">
                <h3>Question de l'étape</h3>
                <div class="question-indices">
                    <?php if (!empty($etape['indice_texte'])): ?>
                        <a href="#" id="show-indice-texte">Indice texte</a>
                    <?php endif; ?>
                    <?php if (!empty($etape['indice_image'])): ?>
                        <a href="#" id="show-indice-image">Indice visuel</a>
                    <?php endif; ?>
                </div>
                <?= $message ?>
                <?php if (!$reponse_validee): ?>
                <?php if ($geo == 1 && !empty($etape['lat']) && !empty($etape['lng'])): ?>
                <div id="geo-message" style="margin-bottom:0.8rem;"></div>
                <div id="geo-loader" style="margin-bottom:0.8rem; display:none;">Recherche de votre position...</div>
                <div id="geo-error" style="color:red; margin-bottom:0.8rem;"></div>
                <?php endif; ?>
                <form class="etape-form" id="etape-form" method="post" action="?id=<?= $id_parcours ?>&etape=<?= $id_etape ?>&geo=<?= $geo ?>">
                    <div class="etape-question-txt">Question : <?= htmlspecialchars($etape['question_etape']) ?></div>
                    <input type="text" name="reponse" placeholder="Votre réponse..." required>
                    <button type="submit" class="btn">Valider</button>
                </form>
                <?php else: ?>
                    <div class="alert-success">Bonne réponse !</div>
                    <?php
                    // Recherche de l'étape suivante
                    require_once __DIR__ . '/../backend/functions/parcours.php';
                    $etapes = get_etapes_by_parcours($pdo, $id_parcours);
                    $etape_suivante = null;
                    for ($i = 0; $i < count($etapes); $i++) {
                        if ($etapes[$i]['id_etape'] == $id_etape && isset($etapes[$i+1])) {
                            $etape_suivante = $etapes[$i+1];
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
            </div>
        </section>
    </main>
    <div id="popup-indice-texte" class="popup-overlay" style="display:none;">
        <div class="popup-content">
            <button class="popup-close" onclick="closePopup('popup-indice-texte')">&times;</button>
            <div class="popup-indice-texte">
                <?= !empty($etape['indice_texte']) ? nl2br(htmlspecialchars($etape['indice_texte'])) : '' ?>
            </div>
        </div>
    </div>
    <div id="popup-indice-image" class="popup-overlay" style="display:none;">
        <div class="popup-content">
            <button class="popup-close" onclick="closePopup('popup-indice-image')">&times;</button>
            <?php if (!empty($etape['indice_image'])): ?>
                <img src="../data/images/<?= htmlspecialchars($etape['indice_image']) ?>" alt="Indice visuel" class="popup-img">
            <?php endif; ?>
        </div>
    </div>
    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Arras Go. Tous droits réservés.</p>
        </div>
    </footer>
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
        const dLat = toRad(lat2-lat1);
        const dLng = toRad(lng2-lng1);
        const a = Math.sin(dLat/2)**2 + Math.cos(toRad(lat1))*Math.cos(toRad(lat2))*Math.sin(dLng/2)**2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
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
