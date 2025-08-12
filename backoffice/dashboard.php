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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <title>Dashboard - Backoffice</title>
</head>
<body>
    <header>
        <h1>Tableau de bord</h1>
        <nav>
            <ul>
                <li><a href="list_parcours.php">Gérer les parcours</a></li>
                <li><a href="list_etapes.php">Gérer les étapes</a></li>
                <li><a href="list_chapitres.php">Gérer les chapitres</a></li>
                <li><a href="list_personnages.php">Gérer les personnages</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <section>
            <h2>Statistiques</h2>
            <p>Nombre de parcours : <?php echo $parcoursCount; ?></p>
            <p>Nombre d'utilisateurs : <?php echo $usersCount; ?></p>
            <p>Nombre d'étapes : <?php echo $etapesCount; ?></p>
            <p>Nombre de chapitres : <?php echo $chapitresCount; ?></p>
            <p>Nombre de personnages : <?php echo $personnagesCount; ?></p>
        </section>
        
        <section>
            <h2>Informations de gestion</h2>
            <p>Dernières activités...</p>
            <!-- Ajouter des informations supplémentaires ici -->
        </section>
    </main>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arras Go. Tous droits réservés.</p>
    </footer>
    
    <script src="js/admin.js"></script>
</body>
</html>