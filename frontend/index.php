<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Arras Go</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <h1>Bienvenue sur Arras Go</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="list_parcours.php">Parcours</a></li>
                <li><a href="jeu.php">Jeu</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>À propos du jeu</h2>
            <p>Arras Go est un jeu interactif qui vous permet de découvrir des parcours passionnants tout en vous amusant.</p>
        </section>
        <section>
            <h2>Commencez votre aventure</h2>
            <p>Explorez nos parcours disponibles et choisissez celui qui vous intéresse le plus.</p>
            <a href="list_parcours.php" class="btn">Voir les parcours</a>
        </section>
    </main>
    <footer>
        <p>&copy; <span id="year"></span> Arras Go. Tous droits réservés.</p>
        <script>
            document.getElementById('year').textContent = new Date().getFullYear();
        </script>
    </footer>
    <script src="js/script.js"></script>
</body>

</html>