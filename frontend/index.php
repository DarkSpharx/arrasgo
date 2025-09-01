<?php
// Connexion à la BDD
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../backend/functions/parcours.php';

// Récupération des parcours
if (function_exists('readParcours')) {
	$parcours = readParcours($pdo);
} elseif (function_exists('get_all_parcours')) {
	$parcours = get_all_parcours($pdo);
} else {
	// fallback : requête directe
	$stmt = $pdo->query('SELECT * FROM parcours WHERE statut = 1 ORDER BY id_parcours DESC');
	$parcours = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Arras Go - Découverte des parcours</title>
	<link rel="stylesheet" href="css/style.css">
</head>

<body>
	<header class="main-header">
		<div class="logo">
			<a href="index.php">
				<img src="./media/logo/logo_long_white_color.svg" alt="Arras Go Logo">
			</a>
		</div>
		<button id="menu-toggle" aria-label="Ouvrir le menu">☰</button>
		<nav id="main-nav" class="main-nav">
			<ul>
				<li><a href="index.php">Accueil</a></li>
				<li><a href="parcours.php">Parcours</a></li>
				<li><a href="personnages.php">Personnages</a></li>
			</ul>
		</nav>
	</header>

	<main>
		<section class="hero">
			<h2>Découvrez Arras autrement !</h2>
			<p>Explorez des parcours interactifs, répondez à des énigmes et plongez dans l’histoire d’Arras.</p>
			<div class="cta-group">
				<a href="parcours.php" class="btn cta">Voir les parcours</a>
				<a href="personnages.php" class="btn cta">Découvrir les personnages</a>
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