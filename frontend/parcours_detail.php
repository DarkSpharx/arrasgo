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
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Parcours introuvable</title></head><body><h1>Parcours introuvable</h1><p>Le parcours demandé n\'existe pas.</p></body></html>';
    exit;
}
// Récupérer la première étape du parcours
$etapes = get_etapes_by_parcours($pdo, $id);
$premiere_etape_id = !empty($etapes) ? $etapes[0]['id_etape'] : null;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($parcours['nom_parcours']) ?> - Parcours</title>
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
    <main>
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
    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Arras Go. Tous droits réservés.</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>

</html>