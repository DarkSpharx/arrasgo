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

// Récupération de la liste des personnages pour l'affichage
$personnages = $pdo->query("SELECT id_personnage, nom_personnage FROM personnages ORDER BY id_personnage DESC")->fetchAll(PDO::FETCH_ASSOC);


// Récupération des parcours en ligne et hors ligne
$parcoursEnLigne = $pdo->query("SELECT id_parcours, nom_parcours FROM parcours WHERE statut = 1 ORDER BY nom_parcours ASC")->fetchAll(PDO::FETCH_ASSOC);
$parcoursHorsLigne = $pdo->query("SELECT id_parcours, nom_parcours FROM parcours WHERE statut = 0 ORDER BY nom_parcours ASC")->fetchAll(PDO::FETCH_ASSOC);

// Comptage des parcours en ligne et hors ligne
$parcoursEnLigneCount = count($parcoursEnLigne);
$parcoursHorsLigneCount = count($parcoursHorsLigne);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style_backoffice.css">
    <link rel="stylesheet" href="css/header_footer.css">
    <link rel="stylesheet" href="css/cards.css">
    <link rel="stylesheet" href="css/alertes.css">
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
                    <h2>Parcours</h2>

                    <a href="list_parcours.php" class="button-bis">Voir tous les parcours</a>

                    <ul>
                        <li>Nombre de parcours : <strong><?php echo $parcoursCount; ?></strong></li>
                        <li>Nombre de parcours en ligne : <strong><?php echo $parcoursEnLigneCount; ?></strong></li>
                        <li>Nombre de parcours hors ligne : <strong><?php echo $parcoursHorsLigneCount; ?></strong></li>
                    </ul>

                    <h3>En ligne</h3>
                    <?php if (count($parcoursEnLigne) > 0): ?>
                        <ul>
                            <?php foreach ($parcoursEnLigne as $p): ?>
                                <li>
                                    <?= htmlspecialchars($p['nom_parcours']) ?>
                                    <a href="edit_parcours.php?id=<?= urlencode($p['id_parcours']) ?>" class="button-card">Modifier</a>
                                    <a href="list_etapes.php?id_parcours=<?= urlencode($p['id_parcours']) ?>" class="button-card-bis">Voir les étapes</a>
                                    <?php
                                    // Récupérer les étapes du parcours en ligne courant
                                    $stmtEtapes = $pdo->prepare("SELECT id_etape, titre_etape FROM etapes WHERE id_parcours = ? ORDER BY id_etape ASC");
                                    $stmtEtapes->execute([$p['id_parcours']]);
                                    $etapes = $stmtEtapes->fetchAll(PDO::FETCH_ASSOC);
                                    ?>

                                    <?php if (count($etapes) > 0): ?>
                                        <ul class="etapes-list">
                                            <?php foreach ($etapes as $etape): ?>
                                                <li>
                                                    <?= htmlspecialchars($etape['titre_etape']) ?>
                                                    <?php
                                                    $stmtChapitres = $pdo->prepare("SELECT titre_chapitre FROM chapitres WHERE id_etape = ? ORDER BY id_chapitre ASC");
                                                    $stmtChapitres->execute([$etape['id_etape']]);
                                                    $chapitres = $stmtChapitres->fetchAll(PDO::FETCH_ASSOC);
                                                    ?>
                                                    <ul class="chapitres-list">
                                                        <?php if (count($chapitres) > 0): ?>
                                                            <?php foreach ($chapitres as $chapitre): ?>
                                                                <li>
                                                                    <?php
                                                                    $titre = isset($chapitre['titre_chapitre']) ? trim($chapitre['titre_chapitre']) : '';
                                                                    echo $titre !== '' ? htmlspecialchars($titre) : '<span style="color: #888; font-size: 14px; font-style: italic;">Ce chapitre n\'a pas de titre</span>';
                                                                    ?>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <li><span style="color: #888; font-size: 14px; font-style: italic;">Aucun chapitre</span></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <ul class="etapes-list">
                                            <li><span style="color: #888; font-size: 14px; font-style: italic;">Aucune étape</span></li>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucun parcours en ligne.</p>
                    <?php endif; ?>

                    <h3>Hors ligne (brouillon)</h3>
                    <?php if (count($parcoursHorsLigne) > 0): ?>
                        <ul>
                            <?php foreach ($parcoursHorsLigne as $p): ?>
                                <li>
                                    <?= htmlspecialchars($p['nom_parcours']) ?>
                                    <a href="edit_parcours.php?id=<?= urlencode($p['id_parcours']) ?>" class="button-card">Modifier</a>
                                    <a href="list_etapes.php?id_parcours=<?= urlencode($p['id_parcours']) ?>" class="button-card-bis">Voir les étapes</a>
                                    <?php
                                    // Récupérer les étapes du parcours hors ligne courant
                                    $stmtEtapes = $pdo->prepare("SELECT id_etape, titre_etape FROM etapes WHERE id_parcours = ? ORDER BY id_etape ASC");
                                    $stmtEtapes->execute([$p['id_parcours']]);
                                    $etapes = $stmtEtapes->fetchAll(PDO::FETCH_ASSOC);
                                    ?>
                                    <?php if (count($etapes) > 0): ?>
                                        <ul class="etapes-list">
                                            <?php foreach ($etapes as $etape): ?>
                                                <li>
                                                    <?= htmlspecialchars($etape['titre_etape']) ?>
                                                    <?php
                                                    // Récupérer les chapitres de cette étape
                                                    $stmtChapitres = $pdo->prepare("SELECT titre_chapitre FROM chapitres WHERE id_etape = ? ORDER BY id_chapitre ASC");
                                                    $stmtChapitres->execute([$etape['id_etape']]);
                                                    $chapitres = $stmtChapitres->fetchAll(PDO::FETCH_ASSOC);
                                                    ?>
                                                    <ul class="chapitres-list">
                                                        <?php if (count($chapitres) > 0): ?>
                                                            <?php foreach ($chapitres as $chapitre): ?>
                                                                <li>
                                                                    <?php
                                                                    $titre = isset($chapitre['titre_chapitre']) ? trim($chapitre['titre_chapitre']) : '';
                                                                    echo $titre !== '' ? htmlspecialchars($titre) : '<span style="color: #888; font-size: 14px; font-style: italic;">Ce chapitre n\'a pas de titre</span>';
                                                                    ?>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <li><span style="color: #888; font-size: 14px; font-style: italic;">Aucun chapitre</span></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <ul class="etapes-list">
                                            <li><span style="color: #888; font-size: 14px; font-style: italic;">Aucune étape</span></li>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucun parcours hors ligne.</p>
                    <?php endif; ?>
                </div>

                <div class="card">
                    <h2>Personnalités</h2>

                    <a href="list_personnages.php" class="button-bis">Voir tous les Personnalités</a>

                    <ul>
                        <li>Nombre de personnalités : <strong><?php echo $personnagesCount; ?></strong></li>
                    </ul>

                    <h3>Liste des Personnalités</h3>
                    <ul>
                        <?php if (count($personnages) > 0): ?>
                            <?php foreach ($personnages as $p): ?>
                                <li>
                                    <?= htmlspecialchars($p['nom_personnage']) ?>
                                    <a href="edit_personnage.php?id=<?= urlencode($p['id_personnage']) ?>" class="button-card">Modifier</a>
                                    <?php
                                    // Récupérer les parcours liés à ce personnage
                                    $stmt = $pdo->prepare("SELECT parcours.id_parcours, parcours.nom_parcours 
                                        FROM parcours 
                                        JOIN parcours_personnages ON parcours.id_parcours = parcours_personnages.id_parcours 
                                        WHERE parcours_personnages.id_personnage = ?");
                                    $stmt->execute([$p['id_personnage']]);
                                    $parcoursLies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    ?>

                                    <?php if (count($parcoursLies) > 0): ?>
                                        <ul class="parcours-lies">
                                            <?php foreach ($parcoursLies as $parcours): ?>
                                                <li>
                                                    <?= htmlspecialchars($parcours['nom_parcours']) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <span style="color: #888; font-size: 14px; font-style: italic;">Aucun parcours lié</span>
                                    <?php endif; ?>

                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun personnage trouvé.</p>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>

</body>


</html>