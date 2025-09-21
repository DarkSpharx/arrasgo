<?php
// Connexion à la BDD
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../backend/functions/parcours.php';
require_once __DIR__ . '/../backend/functions/personnages.php';

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

// Récupération des personnages (en ligne uniquement)
$personnages = function_exists('get_all_personnages')
	? get_all_personnages($pdo)
	: $pdo->query('SELECT * FROM personnages WHERE statut = 1 ORDER BY id_personnage DESC')->fetchAll(PDO::FETCH_ASSOC);
$personnages_en_ligne = $personnages;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="robots" content="index, follow">
	<meta name="author" content="Musée des Beaux-Arts d’Arras">
	<meta name="description" content="Arras Go est un jeu gratuit et immersif en centre-ville d’Arras. Vivez une enquête autour de l’histoire du théâtre d’Arras !" />
	<meta name="robots" content="index, follow">
	<meta name="author" content="Musée des Beaux-Arts d’Arras">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/footer.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<meta
		property="og:title"
		content="Arras Go - Jeu piéton gratuit en centre-ville d'Arras" />
	<meta
		property="og:description"
		content="Une expérience ludique et immersive autour de l’histoire et du patrimoine d’Arras" />
	<meta property="og:image" content="media/image/bg_pc.webp" />
	<meta property="og:url" content="https://arras-go.fr/" />
	<meta property="og:type" content="website" />
	<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
	<link rel="icon" type="image/svg+xml" href="media/favicon/favicon.svg" />
	<link rel="icon" type="image/png" href="media/favicon/favicon-96x96.png" sizes="96x96" />
	<link rel="shortcut icon" href="media/favicon/favicon.ico" />
	<link rel="apple-touch-icon" sizes="180x180" href="media/favicon/apple-touch-icon.png" />
	<meta name="apple-mobile-web-app-title" content="Arras Go" />
	<link rel="manifest" href="media/favicon/site.webmanifest" />
	<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
	<title>Arras Go - Le jeu piéton et gratuit d'Arras</title>
</head>

<body>
	<header>
		<div>
			<a href="index.php">
				<img src="./media/logo/logo_long_monochrome_white.svg" alt="Arras Go Logo" class="left">
			</a>
			<button id="menu-toggle" aria-label="Ouvrir le menu" class="right"><i class="fa-solid fa-bars"></i></button>
			<nav id="main-nav" class="main-nav">
				<ul>
					<li><a href="index.php">Accueil</a></li>
					<li><a href="parcours.php">Parcours</a></li>
					<li><a href="personnages.php">Personnages</a></li>
				</ul>
			</nav>
		</div>
	</header>

	<main class="main">
		<section class="hero">
			<div class="down">
				<img src="./media/logo/pin.svg" class="float" alt="Icone de localisation">
			</div>
			<img src="./media/logo/cercle.svg" alt="Cercle décoratif">
			<h1>Bienvenue sur Arras Go</h1>
			<hr class="zoom">
			<h2 class="left">Le jeu piéton d'Arras</h2>
			<a href="parcours.php" class="btn cta">Jouer !</a>
		</section>

		<section class="container e7d8c9">
			<div class="corps">
				<h2>Une expérience ludique et immersive autour<br>de l’histoire et du patrimoine d’Arras.</h2>
				<hr>
				<em>Réalisé à partir des collections du Pôle culturel Saint-Vaast.</em>
			</div>
		</section>

		<section class="container fff">
			<div class="corps">
				<h2>2 modes de jeu</h2>
				<div class="two-column">
					<div class="column">
						<h3>Le Mode explorateur
							<br>
							<p>(avec géolocalisation)</p>
						</h3>
						<p>Partez dans les rues d'Arras avec votre smartphone et vivez l'enquête en temps réel au cœur de la ville.</p>
					</div>
					<div class="column">
						<h3>Le Mode découverte
							<br>
							<p>(sans géolocalisation)</p>
						</h3>
						<p>Explorez les énigmes depuis un ordinateur, chez vous ou en classe, sans vous déplacer physiquement.</p>
					</div>
				</div>
				<hr>
				<em>Chaque étape du jeu est accompagnée d’un lecteur audio avec une retranscription audio du texte afin de faciliter la jouabilité de manière plus immersive.</em>
			</div>
		</section>

		<section class="container e7d8c9">
			<div class="corps">
				<h2>Parcours disponibles</h2>
				<hr>
				<div class="parcours-cards">
					<?php
					$parcours_en_ligne = array_filter($parcours ?? [], function ($p) {
						return isset($p['statut']) && $p['statut'] == 1;
					});
					?>
					<?php if (!empty($parcours_en_ligne)): ?>
						<?php foreach ($parcours_en_ligne as $p): ?>
							<article class="parcours-card">
								<a href="parcours_detail.php?id=<?= urlencode($p['id_parcours']) ?>" class="parcours-card-link" tabindex="-1" aria-hidden="true" style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:1;"></a>
								<?php if (!empty($p['image_parcours'])): ?>
									<img src="../data/images/<?= htmlspecialchars($p['image_parcours']) ?>" alt="Image du parcours" class="parcours-img">
								<?php else: ?>
									<div class="parcours-img placeholder"></div>
								<?php endif; ?>
								<div class="parcours-content" style="position:relative;z-index:2;">
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

		<section class="container fff">
			<div class="corps">
				<h2>Découvrez ARRAS via ces personnalités</h2>
				<hr>
				<em>Chaque fiche personnage est accompagnée d’un lecteur audio avec une retranscription audio du texte afin de faciliter la découverte de ses personnalités emblématiques d'ARRAS de manière plus immersive.</em>
			</div>
		</section>

		<section class="container e7d8c9">
			<div class="corps">
				<h2>les fiches des personnalités</h2>
				<hr>
				<div class="parcours-cards">
					<?php if (!empty($personnages_en_ligne)): ?>
						<?php foreach ($personnages_en_ligne as $perso): ?>
							<a href="personnage.php?id=<?= urlencode($perso['id_personnage']) ?>" class="parcours-card-link">
								<article class="parcours-card">
									<a href="personnage.php?id=<?= urlencode($perso['id_personnage']) ?>" class="parcours-card-link" tabindex="-1" aria-hidden="true" style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:1;"></a>
									<?php if (!empty($perso['image_personnage'])): ?>
										<img src="../data/images/<?= htmlspecialchars($perso['image_personnage']) ?>" alt="Image du personnage" class="parcours-img">
									<?php else: ?>
										<div class="parcours-img placeholder"></div>
									<?php endif; ?>
									<div class="parcours-content" style="position:relative;z-index:2;">
										<h3><?= htmlspecialchars($perso['nom_personnage'] ?? $perso['nom'] ?? 'Personnage') ?></h3>
										<a href="personnage.php?id=<?= urlencode($perso['id_personnage']) ?>" class="btn">Voir la fiche</a>
									</div>
								</article>
							</a>
						<?php endforeach; ?>
					<?php else: ?>
						<p>Aucun personnage disponible pour le moment.</p>
					<?php endif; ?>
				</div>
			</div>
		</section>
	</main>

	<?php include __DIR__ . '/footer.php'; ?>

	<script src="js/script.js"></script>
</body>

</html>