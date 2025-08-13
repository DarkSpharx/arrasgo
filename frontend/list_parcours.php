<?php
require_once '../backend/config/database.php';
require_once '../backend/functions/parcours.php';
require_once '../backend/functions/etapes.php';
require_once '../backend/functions/chapitres.php';

$parcours = get_all_parcours($pdo);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Parcours Disponibles</title>
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <header>
    <h1>Parcours Disponibles</h1>
    <nav>
      <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="list_parcours.php">Parcours</a></li>
        <li><a href="jeu.php">Jeu</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <section id="parcours-list">
      <h2>Liste des Parcours</h2>
      <ul>
        <?php foreach ($parcours as $p): ?>
          <li>
            <h3><?= htmlspecialchars($p['nom']) ?></h3>
            <p><?= htmlspecialchars($p['description']) ?></p>
            <?php if (!empty($p['image_parcours'])): ?>
              <img src="/data/images/<?= htmlspecialchars($p['image_parcours']) ?>" alt="Image parcours" style="max-width:150px;max-height:150px;">
            <?php endif; ?>
            <form method="get" action="etape.php">
              <input type="hidden" name="id_parcours" value="<?= $p['id'] ?>">
              <label>
                <input type="radio" name="mode_geo" value="false" checked> Sans géolocalisation
              </label>
              <label>
                <input type="radio" name="mode_geo" value="true"> Avec géolocalisation
              </label>
              <button type="submit" class="button">Commencer le parcours</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>
    <?php
    if (isset($_GET['id_parcours'])) {
      $etapes = get_etapes_by_parcours($pdo, intval($_GET['id_parcours']));
      echo '<section id="etapes-list"><h2>Étapes du parcours</h2>';
      foreach ($etapes as $etape) {
        echo '<div class="etape">';
        echo '<h3>' . htmlspecialchars($etape['titre_etape']) . '</h3>';
        if (!empty($etape['mp3_etape'])) {
          echo '<audio src="/data/mp3/' . htmlspecialchars($etape['mp3_etape']) . '" controls></audio>';
        }
        if (!empty($etape['indice_etape_texte'])) {
          echo '<p>Indice texte : ' . htmlspecialchars($etape['indice_etape_texte']) . '</p>';
        }
        if (!empty($etape['indice_etape_image'])) {
          echo '<img src="/data/images/' . htmlspecialchars($etape['indice_etape_image']) . '" alt="Indice image">';
        }
        if (!empty($etape['question_etape'])) {
          echo '<p>Question : ' . htmlspecialchars($etape['question_etape']) . '</p>';
        }
        // Formulaire de réponse
        echo '<form class="reponse-form"><input type="text" name="reponse" placeholder="Votre réponse..." required><button type="submit" class="button">Valider</button></form>';
        // Affichage des chapitres
        $chapitres = get_chapitres_by_etape($pdo, $etape['id_etape']);
        if ($chapitres) {
          echo '<div class="chapitres">';
          foreach ($chapitres as $chapitre) {
            echo '<div class="chapitre">';
            echo '<h4>' . htmlspecialchars($chapitre['titre_chapitre']) . '</h4>';
            if (!empty($chapitre['image_chapitre'])) {
              echo '<img src="/data/images/' . htmlspecialchars($chapitre['image_chapitre']) . '" alt="Image chapitre">';
            }
            echo '<p>' . htmlspecialchars($chapitre['texte_chapitre']) . '</p>';
            echo '</div>';
          }
          echo '</div>';
        }
        echo '</div>';
      }
      echo '</section>';
    }
    ?>
  </main>

  <footer>
    <p>&copy; <span id="year"></span> Arras Go. Tous droits réservés.</p>
    <script>
      document.getElementById("year").textContent = new Date().getFullYear();
    </script>
  </footer>
</body>

</html>
<script>
  <?php if (isset($_GET['id_parcours'])): ?>
    window.location.href = 'etape.php?id_parcours=' + <?= json_encode($_GET['id_parcours']) ?>;
  <?php endif; ?>
</script>