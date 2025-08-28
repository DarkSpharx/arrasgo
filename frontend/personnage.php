<?php
// Détail d'un personnage
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../backend/functions/personnages.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$personnage = $id ? get_personnage($pdo, $id) : null;

if (!$personnage) {
    http_response_code(404);
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Personnalité introuvable</title></head><body><h1>Personnalité introuvable</h1><p>Le personnage demandé n\'existe pas.</p></body></html>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($personnage['nom_personnage']) ?> - Détail</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1>Arras Go</h1>
            <button id="menu-toggle" aria-label="Ouvrir le menu">☰</button>
        </div>
        <nav id="main-nav" class="main-nav">
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="parcours.php">Parcours</a></li>
                <li><a href="personnages.php" class="active">Personnages</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="detail-personnage">
            <div class="detail-card">
                <?php if (!empty($personnage['image_personnage'])): ?>
                    <img src="../data/images/<?= htmlspecialchars($personnage['image_personnage']) ?>" alt="Portrait de <?= htmlspecialchars($personnage['nom_personnage']) ?>" class="detail-img">
                <?php endif; ?>
                <div class="detail-content">
                    <h2><?= htmlspecialchars($personnage['nom_personnage']) ?></h2>
                    <p><?= nl2br(htmlspecialchars($personnage['description_personnage'])) ?></p>
                    <a href="personnages.php" class="btn">← Retour à la liste</a>
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
