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
		<section class="hero">
			<img src="./media/logo/pin.svg" class="float ">
			<img src="./media/logo/cercle.svg">
			<h1>Bienvenue sur Arras Go</h1>
			<hr>
			<h2>Jeu piéton gratuit en centre-ville d'Arras</h2>
			<a href="parcours.php" class="btn cta">Jouer !</a>
		</section>

		<section class="container">
			<h2>Une expérience ludique et immersive autour<br>de l’histoire et du patrimoine d’Arras.</h2>
			<em>Réalisé à partir des collections du Pôle culturel Saint-Vaast.</em>
			<hr>
			<div>
				<h2>2 modes de jeu</h2>
				<div class="two-column">
					<div class="column">
						<h3>Le Mode explorateur<br>(avec géolocalisation)</h3>
						<p>Partez dans les rues d'Arras avec votre smartphone et vivez l'enquête en temps réel au cœur de la ville.</p>
					</div>
					<div class="column">
						<h3>Le Mode découverte<br>(sans géolocalisation)</h3>
						<p>Explorez les énigmes depuis un ordinateur, chez vous ou en classe, sans vous déplacer physiquement.</p>
					</div>
				</div>
			</div>
			<em>Chaque étape du jeu est accompagnée d’un lecteur audio pour faciliter la jouabilité en déambulant librement dans la ville.</em>
		</section>

		<section class="container">
			<div class="parcours-list">
				<h2>Parcours disponibles</h2>
				<div class="parcours-cards">
					<?php 
					$parcours_en_ligne = array_filter($parcours ?? [], function($p) {
						return isset($p['statut']) && $p['statut'] == 1;
					});
					?>
					<?php if (!empty($parcours_en_ligne)): ?>
						<?php foreach ($parcours_en_ligne as $p): ?>
							<article class="parcours-card">
								<?php if (!empty($p['image_parcours'])): ?>
									<img src="../data/images/<?= htmlspecialchars($p['image_parcours']) ?>" alt="Image du parcours" class="parcours-img">
								<?php else: ?>
									<div class="parcours-img placeholder"></div>
								<?php endif; ?>
								<div class="parcours-content">
									<h3><?= htmlspecialchars($p['nom_parcours'] ?? $p['name'] ?? 'Parcours') ?></h3>
									<p><?= htmlspecialchars($p['description_parcours'] ?? $p['description'] ?? '') ?></p>
									<a href="parcours_detail.php?id=<?= urlencode($p['id_parcours']) ?>" class="btn">Voir le parcours</a>
								</div>
							</article>
						<?php endforeach; ?>
					<?php else: ?>
						<p>Aucun parcours disponible pour le moment.</p>
					<?php endif; ?>
				</div>
			</div>
		</section>
	</main>

	<footer class="main-footer">
		<div>
			<p>&copy; <?= date('Y') ?> Arras Go. Tous droits réservés.</p>
		</div>
	</footer>

	<script src="js/script.js"></script>
</body>

</html>