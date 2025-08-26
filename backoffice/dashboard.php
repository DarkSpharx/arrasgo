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
    <link rel="stylesheet" href="css/tab.css">
    <script src="js/admin.js" defer></script>
    <title>Dashboard - ARRAS GO</title>
</head>

<body>
    <?php include 'header.php'; ?>

    <h1 class="h1-sticky">ARRAS GO - Dashboard</h1>

    <main>
        <div class="cards-container">
            <div class="cards-grid">
                <div class="card-button">
                    <a href="add_parcours.php" class="button">+ Ajouter un parcours</a>
                    <a href="add_personnage.php" class="button">+ Ajouter une personnalité</a>
                </div>

                <div class="card">
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
                </div>

                <div class="card">
                    <h2>Statistiques</h2>
                    <p>Nombre de parcours : <strong><?php echo $parcoursCount; ?></strong></p>
                    <p>Nombre d'utilisateurs : <strong><?php echo $usersCount; ?></strong></p>
                    <p>Nombre d'étapes : <strong><?php echo $etapesCount; ?></strong></p>
                    <p>Nombre de chapitres : <strong><?php echo $chapitresCount; ?></strong></p>
                    <p>Nombre de personnages : <strong><?php echo $personnagesCount; ?></strong></p>
                </div>

                <?php
                // Récupération des parcours en ligne et hors ligne
                $parcoursEnLigne = $pdo->query("SELECT id_parcours, nom_parcours FROM parcours WHERE statut = 1 ORDER BY nom_parcours ASC")->fetchAll(PDO::FETCH_ASSOC);
                $parcoursHorsLigne = $pdo->query("SELECT id_parcours, nom_parcours FROM parcours WHERE statut = 0 ORDER BY nom_parcours ASC")->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <div class="card">
                    <h2>Parcours en ligne</h2>
                    <?php if (count($parcoursEnLigne) > 0): ?>
                        <ul>
                            <?php foreach ($parcoursEnLigne as $p): ?>
                                <li>
                                    <?= htmlspecialchars($p['nom_parcours']) ?>
                                    <a href="edit_parcours.php?id=<?= $p['id_parcours'] ?>" class="button-tab" style="margin-left:10px;">Modifier</a>
                                    <a href="list_etapes.php?id_parcours=<?= $p['id_parcours'] ?>" class="button-tab" style="margin-left:10px;">Voir les étapes</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucun parcours en ligne.</p>
                    <?php endif; ?>
                </div>

                <div class="card">
                    <h2>Parcours hors ligne (brouillon)</h2>
                    <?php if (count($parcoursHorsLigne) > 0): ?>
                        <ul>
                            <?php foreach ($parcoursHorsLigne as $p): ?>
                                <li>
                                    <?= htmlspecialchars($p['nom_parcours']) ?>
                                    <a href="edit_parcours.php?id=<?= $p['id_parcours'] ?>" class="button-tab" style="margin-left:10px;">Modifier</a>
                                    <a href="list_etapes.php?id_parcours=<?= $p['id_parcours'] ?>" class="button-tab" style="margin-left:10px;">Voir les étapes</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucun parcours hors ligne.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>

</body>

    <link rel="stylesheet" href="css/alertes.css">
</html>