<?php
require_once '../backend/config/database.php';
require_once '../backend/functions/etapes.php';
require_once '../backend/functions/chapitres.php';

$id_parcours = isset($_GET['id_parcours']) ? intval($_GET['id_parcours']) : 0;
$mode_geo = isset($_GET['mode_geo']) ? $_GET['mode_geo'] === 'true' : false;
$ordre = isset($_GET['ordre']) ? intval($_GET['ordre']) : 1;

// Récupère toutes les étapes du parcours
$etapes = get_etapes_by_parcours($pdo, $id_parcours);

// Sélectionne l’étape courante
$etape = null;
foreach ($etapes as $e) {
    if ($e['ordre_etape'] == $ordre) {
        $etape = $e;
        break;
    }
}

if (!$etape) {
    echo "<h2>Félication vous aves terminé le parcours !</h2>";
    echo "<p>Vous pouvez retourner à l'accueil ou choisir un autre parcours.</p>";

    echo "<div style='margin-top:20px;'>";
    echo "<a href='index.php' class='button' style='margin-right:10px;'>Retour à l'accueil</a>";
    echo "<a href='parcours.php' class='button'>Voir nos parcours</a>";
    echo "</div>";

    exit;
}


// Gestion du formulaire de réponse
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($etape['question_etape'])) {
    $reponse = trim($_POST['reponse']);
    $bonne_reponse = strtolower(trim($etape['reponse_attendue']));
    $reponse_ok = strtolower($reponse) === $bonne_reponse;

    // Géolocalisation
    $geo_ok = true;
    if ($mode_geo && !empty($etape['latitude']) && !empty($etape['longitude'])) {
        if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
            $user_lat = floatval($_POST['latitude']);
            $user_lng = floatval($_POST['longitude']);
            $distance = calculerDistance($user_lat, $user_lng, floatval($etape['latitude']), floatval($etape['longitude']));
            $geo_ok = $distance <= 25;
        } else {
            $geo_ok = false;
        }
    }

    $next_ordre = $ordre + 1; // Ajoute cette ligne ici

    if ($reponse_ok && $geo_ok) {
        // Affiche le popup et redirige automatiquement
        $message = "<div id='popup-success' class='popup'>
            <div class='popup-content'>
                <span class='close' onclick=\"document.getElementById('popup-success').style.display='none';\">&times;</span>
                <h3>Bravo, vous avez bien répondu !</h3>
                <p>La suite de l'aventure vous attends...</p>
            </div>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = 'etapes.php?id_parcours=$id_parcours&mode_geo=" . ($mode_geo ? 'true' : 'false') . "&ordre=$next_ordre';
            }, 4000);
        </script>";
    } elseif (!$reponse_ok) {
        $message = "<div class='error'>Mauvaise réponse !</div>";
    } elseif ($mode_geo && !$geo_ok) {
        $message = "<div class='error'>Bonne réponse, mais vous n'êtes pas au bon endroit (plus de 25m).</div>";
    }
}

function calculerDistance($lat1, $lng1, $lat2, $lng2)
{
    $R = 6371000;
    $dLat = ($lat2 - $lat1) * pi() / 180;
    $dLng = ($lng2 - $lng1) * pi() / 180;
    $a = sin($dLat / 2) * sin($dLat / 2) +
        cos($lat1 * pi() / 180) * cos($lat2 * pi() / 180) *
        sin($dLng / 2) * sin($dLng / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $R * $c;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Étape du parcours</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main>
        <section class="container">
            <div class="etape-row">
                <div class="etape-img">
                    <?php if (!empty($etape['indice_etape_image'])): ?>
                        <button type="button" class="button" onclick="document.getElementById('popup-indice-image').style.display='block';">Indice 2</button>
                        <div id="popup-indice-image" class="popup" style="display:none;">
                            <div class="popup-content">
                                <span class="close" onclick="document.getElementById('popup-indice-image').style.display='none';">&times;</span>
                                <h3>Indice visuel</h3>
                                <img src="/data/images/<?= htmlspecialchars($etape['indice_etape_image']) ?>" alt="Indice image" style="max-width:100%;">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="etape-content">
                    <h2><?= htmlspecialchars($etape['titre_etape']) ?></h2>
                    <?php if (!empty($etape['mp3_etape'])): ?>
                        <audio src="/data/mp3/<?= htmlspecialchars($etape['mp3_etape']) ?>" controls></audio>
                    <?php endif; ?>
                    <?php if (!empty($etape['indice_etape_texte'])): ?>
                        <button type="button" class="button" onclick="document.getElementById('popup-indice-texte').style.display='block';">Indice 1</button>
                        <div id="popup-indice-texte" class="popup" style="display:none;">
                            <div class="popup-content">
                                <span class="close" onclick="document.getElementById('popup-indice-texte').style.display='none';">&times;</span>
                                <h3>Indice textuel</h3>
                                <p><?= htmlspecialchars($etape['indice_etape_texte']) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="QuestionReponse">
                        <div class="question-text">
                            <?php if (!empty($etape['question_etape'])): ?>
                                <label for="reponse"><strong>Question :</strong></label>
                                <p><?= htmlspecialchars($etape['question_etape']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="reponse-form-container">
                            <?php if (!empty($etape['question_etape'])): ?>
                                <form method="POST" class="reponse-form" id="reponseForm">
                                    <input type="text" name="reponse" id="reponse" required placeholder="Votre réponse...">
                                    <?php if ($mode_geo && !empty($etape['latitude']) && !empty($etape['longitude'])): ?>
                                        <input type="hidden" name="latitude" id="latitude">
                                        <input type="hidden" name="longitude" id="longitude">
                                        <div class="geo-info">Votre position sera vérifiée lors de la validation.</div>
                                        <script>
                                            if (navigator.geolocation) {
                                                navigator.geolocation.getCurrentPosition(function(pos) {
                                                    document.getElementById('latitude').value = pos.coords.latitude;
                                                    document.getElementById('longitude').value = pos.coords.longitude;
                                                }, function() {
                                                    document.querySelector('.geo-info').textContent = "Impossible d'obtenir votre position.";
                                                });
                                            }
                                        </script>
                                    <?php endif; ?>
                                    <button type="submit" class="button">Valider</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?= $message ?>
                    <!-- Affichage des chapitres -->
                    <?php
                    $chapitres = get_chapitres_by_etape($pdo, $etape['id_etape']);
                    if ($chapitres) {
                        foreach ($chapitres as $chapitre) {
                            echo '<div class="chapitre-row">';
                            echo '<div class="chapitre-img">';
                            if (!empty($chapitre['image_chapitre'])) {
                                echo '<img src="/data/images/' . htmlspecialchars($chapitre['image_chapitre']) . '" alt="Image chapitre">';
                            }
                            echo '</div>';
                            echo '<div class="chapitre-content">';
                            echo '<h4>' . htmlspecialchars($chapitre['titre_chapitre']) . '</h4>';
                            echo '<p>' . htmlspecialchars($chapitre['texte_chapitre']) . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
            <!-- Bouton étape suivante -->
            <?php
            $next_ordre = $ordre + 1;
            $has_next = false;
            foreach ($etapes as $e) {
                if ($e['ordre_etape'] == $next_ordre) {
                    $has_next = true;
                    break;
                }
            }
            // Suppression du bouton étape suivante après la validation
            /*
            if ($has_next) {
                echo "<div style='text-align:right; margin-top:30px;'><a href='etapes.php?id_parcours=$id_parcours&mode_geo=" . ($mode_geo ? 'true' : 'false') . "&ordre=$next_ordre' class='button'>Étape suivante</a></div>";
            }
            */
            ?>
        </section>
    </main>
    <?php if (!empty($etape['mp3_etape'])): ?>
        <div class="audio-fixed">
            <audio src="/data/mp3/<?= htmlspecialchars($etape['mp3_etape']) ?>" controls style="width:100%;">
                Votre navigateur ne supporte pas l'audio.
            </audio>
        </div>
    <?php endif; ?>
</body>

</html>