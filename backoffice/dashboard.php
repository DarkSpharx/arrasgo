<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php'; // <-- AJOUTE CETTE LIGNE
require_once '../backend/functions/parcours.php';
require_once '../backend/functions/etapes.php';
require_once '../backend/functions/chapitres.php';
require_once '../backend/functions/personnages.php';

// Récupération des statistiques et informations de gestion
$parcoursCount = getParcoursCount($pdo);
$usersCount = getUsersCount($pdo);
$etapesCount = getEtapesCount($pdo);
$chapitresCount = getChapitresCount($pdo);
$personnagesCount = getPersonnagesCount($pdo);

// Récupération des 5 derniers parcours et étapes
$recentParcours = $pdo->query("SELECT nom_parcours, date_creation_user FROM parcours JOIN users_admins ON parcours.id_user = users_admins.id_user ORDER BY parcours.id_parcours DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
$recentEtapes = $pdo->query("SELECT titre_etape, id_etape FROM etapes ORDER BY id_etape DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Exemple simple
$parcoursSansEtape = $pdo->query("SELECT nom_parcours FROM parcours WHERE id_parcours NOT IN (SELECT DISTINCT id_parcours FROM etapes)")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style_backoffice.css">
    <link rel="stylesheet" href="css/header_footer.css">
    <script src="js/admin.js" defer></script>
    <title>Dashboard - ARRAS GO</title>
</head>

<body>
    <?php include 'header.php'; ?>

    <main>
        <h1>Tableau de bord</h1>
        <div class="container-horizontal">
            <div class="container-vertical">
                <h2>Informations de gestion</h2>
                <h3>Derniers parcours ajoutés</h3>
                <ul>
                    <?php foreach ($recentParcours as $p): ?>
                        <li><?= htmlspecialchars($p['nom_parcours']) ?></li>
                    <?php endforeach; ?>
                </ul>
                <h3>Dernières étapes créées</h3>
                <ul>
                    <?php foreach ($recentEtapes as $e): ?>
                        <li><?= htmlspecialchars($e['titre_etape']) ?></li>
                    <?php endforeach; ?>
                </ul>
                <div>
                    <a href="add_parcours.php" class="button">+ Nouveau parcours</a>
                </div>

                <?php if (count($parcoursSansEtape) > 0): ?>
                    <div class="alert">
                        <strong>Attention :</strong> Les parcours suivants n'ont aucune étape :
                        <ul>
                            <?php foreach ($parcoursSansEtape as $p): ?>
                                <li><?= htmlspecialchars($p['nom_parcours']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>

            <div class="container-vertical">
                <h2>Statistiques</h2>
                <p>Nombre de parcours : <strong><?php echo $parcoursCount; ?></strong></p>
                <p>Nombre d'utilisateurs : <strong><?php echo $usersCount; ?></strong></p>
                <p>Nombre d'étapes : <strong><?php echo $etapesCount; ?></strong></p>
                <p>Nombre de chapitres : <strong><?php echo $chapitresCount; ?></strong></p>
                <p>Nombre de personnages : <strong><?php echo $personnagesCount; ?></strong></p>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>

</body>

</html>