<?php
session_start();
require_once '../backend/security/check_auth.php';
require_once '../backend/config/database.php';
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
$recentParcours = $pdo->query("SELECT parcours.id_parcours, nom_parcours, date_creation_user FROM parcours JOIN users_admins ON parcours.id_user = users_admins.id_user ORDER BY parcours.id_parcours DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
$recentEtapes = $pdo->query("SELECT titre_etape, id_etape FROM etapes ORDER BY id_etape DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// On récupère aussi l'id du parcours pour générer le lien correctement
$parcoursSansEtape = $pdo->query("SELECT id_parcours, nom_parcours FROM parcours WHERE id_parcours NOT IN (SELECT DISTINCT id_parcours FROM etapes)")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les étapes sans parcours (cas rare, mais demandé)
$etapesSansParcours = $pdo->query("SELECT id_etape, titre_etape FROM etapes WHERE id_parcours IS NULL OR id_parcours = ''")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style_backoffice.css">
    <link rel="stylesheet" href="css/header_footer.css">
    <script src="js/admin.js" defer></script>
    <title>Dashboard - ARRAS GO</title>
</head>

<body>
    <?php include 'header.php'; ?>

    <h1 class="h1-sticky">Dashboard</h1>

    <main>
        <div class="container-horizontal">
            <div class="container-vertical">
                <h2>Informations de gestion</h2>
                <h3>Derniers parcours ajoutés</h3>
                <ul>
                    <?php foreach ($recentParcours as $p): ?>
                        <li>
                            <?= htmlspecialchars($p['nom_parcours']) ?>
                            <?php if (!empty($p['id_parcours'])): ?>
                                <a href="list_etapes.php?id_parcours=<?= urlencode($p['id_parcours']) ?>" class="button-tab" style="margin-left:10px;">Voir les étapes</a>
                            <?php else: ?>
                                <span style="color: #b00; margin-left:10px;">ID parcours manquant</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php if (count($parcoursSansEtape) > 0): ?>
                    <div class="alert">
                        <strong>Attention :</strong> Les parcours suivants n'ont aucune étape :
                        <ul>
                            <?php foreach ($parcoursSansEtape as $p): ?>
                                <li>
                                    <?= htmlspecialchars($p['nom_parcours']) ?>
                                    <?php if (!empty($p['id_parcours'])): ?>
                                        <a href="list_etapes.php?id_parcours=<?= urlencode($p['id_parcours']) ?>" class="button-tab" style="margin-left:10px;">Voir les étapes</a>
                                    <?php else: ?>
                                        <span style="color: #b00; margin-left:10px;">ID parcours manquant</span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <h3>Dernières étapes créées</h3>
                <ul>
                    <?php foreach ($recentEtapes as $e): ?>
                        <li>
                            <?= htmlspecialchars($e['titre_etape']) ?>
                            <?php if (!empty($e['id_etape'])): ?>
                                <a href="list_chapitres.php?id_etape=<?= urlencode($e['id_etape']) ?>" class="button-tab" style="margin-left:10px;">Voir les chapitres</a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if (count($etapesSansParcours) > 0): ?>
                    <div class="alert">
                        <strong>Attention :</strong> Les étapes suivantes n'ont aucun parcours associé :
                        <ul>
                            <?php foreach ($etapesSansParcours as $e): ?>
                                <li>
                                    <?= htmlspecialchars($e['titre_etape']) ?>
                                    <?php if (!empty($e['id_etape'])): ?>
                                        <a href="list_chapitres.php?id_etape=<?= urlencode($e['id_etape']) ?>" class="button-tab" style="margin-left:10px;">Voir les chapitres</a>
                                    <?php else: ?>
                                        <span style="color: #b00; margin-left:10px;">ID étape manquant</span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>



                <div class="container-horizontal">
                    <a href="add_parcours.php" class="button">+ Nouveau parcours</a>
                </div>
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