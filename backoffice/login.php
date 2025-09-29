<?php
session_start();

// Vérifie si l'utilisateur est déjà connecté
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

// Traitement du formulaire de connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de configuration de la base de données
    require_once '../backend/config/database.php';
    // inclure la fonction reCAPTCHA
    require_once __DIR__ . '/../backend/functions/recaptcha.php';
    $recaptcha_config = require __DIR__ . '/../backend/config/recaptcha.php';

    // Récupérer les informations du formulaire
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $token = $_POST['g-recaptcha-response'] ?? '';
    // Vérification reCAPTCHA (bypass si clés non configurées pour l'environnement de dev/local)
    if (empty($recaptcha_config['secret']) || empty($recaptcha_config['site_key'])) {
        // Pas de reCAPTCHA configuré : bypass pour l'environnement local / démo
        $verify = ['success' => true, 'score' => 1.0, 'action' => 'login', 'raw' => ['bypass' => true]];
    } else {
        $verify = verify_recaptcha_v3($token, 'login', $recaptcha_config['min_score'] ?? 0.5);
    }

    if (!$verify['success']) {
        $login_error = "Vérification automatique anti-bot échouée (score: " . ($verify['score'] ?? 0) . ").";
    }

    // Pour la connexion, ne pas appliquer une validation de mot de passe trop stricte
    // (cela empêche les comptes existants avec mots de passe hérités). On exige seulement non vide.
    if (empty($login_error) && $password === '') {
        $login_error = 'Mot de passe requis.';
    }

    // Si la connexion à la BDD est absente on stoppe proprement l'authentification
    if (!isset($pdo) || $pdo === null) {
        $login_error = 'Erreur de connexion : base de données indisponible.';
    } else {
        // Requête préparée : recherche de l'admin par email,
        // vérification du mot de passe et ouverture de la session.
        $sql = "SELECT * FROM users_admins WHERE email_user = :username";

        if (empty($login_error) && $stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    // Vérifier le mot de passe
                    if (password_verify($password, $row['mot_de_passe_user'])) {
                        // Démarrer la session et enregistrer les informations de l'utilisateur
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_id'] = $row['id_user'];
                        $_SESSION['admin_username'] = $row['email_user'];

                        // Rediriger vers la page du tableau de bord
                        header("Location: dashboard.php");
                        exit;
                    } else {
                        $login_error = "Mot de passe incorrect.";
                    }
                } else {
                    $login_error = "Aucun compte trouvé avec cet email.";
                }
            } else {
                $login_error = "Erreur lors de l'exécution de la requête.";
            }
            unset($stmt);
        }
        unset($pdo);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style_backoffice.css">
    <link rel="stylesheet" href="css/forms.css">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="css/alertes.css">
    <link rel="icon" type="image/png" href="assets/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="assets/favicon/favicon.svg" />
    <link rel="icon" type="image/png" href="assets/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Arras Go Backoffice" />
    <link rel="manifest" href="assets/favicon/site.webmanifest" />
</head>

<body>
    <main style="min-height: 90vh; display: flex
">
        <div class="form-container">
            <h1>Back-office<br>ARRAS GO</h1>
            <?php if (isset($login_error)) {
                echo '<p class="error">' . $login_error . '</p>';
            }
            // afficher un avertissement si reCAPTCHA n'est pas configuré
            $recaptcha_config = require __DIR__ . '/../backend/config/recaptcha.php';
            if (empty($recaptcha_config['site_key']) || empty($recaptcha_config['secret'])) {
                echo '<p class="info">reCAPTCHA non configuré — authentification en mode démo (sécurité limitée).</p>'; 
            }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="username">Adresse e-mail</label>
                    <input type="text" name="username" id="username" placeholder="Adresse e-mail" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" placeholder="Mot de passe" required>
                </div>
                <!-- message d'erreur côté client -->
                <p id="password-error" class="error" style="display:none; color:#a00; margin-top:0.5rem"></p>
                <!-- champ pour reCAPTCHA token -->
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" value="">
                <button type="submit" class="button">Se connecter</button>
            </form>
            <!-- reCAPTCHA v3: charger la librairie et exécuter pour action 'login' -->
            <script src="https://www.google.com/recaptcha/api.js?render=<?php echo htmlspecialchars($recaptcha_config['site_key'] ?? ''); ?>"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var siteKey = '<?php echo addslashes($recaptcha_config['site_key'] ?? ''); ?>';
                    if (!siteKey) return;
                    grecaptcha.ready(function() {
                        var form = document.querySelector('form[action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"]');
                        if (!form) return;
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            grecaptcha.execute(siteKey, {
                                action: 'login'
                            }).then(function(token) {
                                var inp = document.getElementById('g-recaptcha-response');
                                if (inp) inp.value = token;
                                form.submit();
                            });
                        });
                    });
                });
            </script>
            <script>
                (function() {
                    // Même pattern que côté serveur (stricte : pas d'espaces, >=8, 1 maj, 1 min, 1 chiffre, 1 spécial)
                    var pattern = /^(?=.{8,}$)(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9])\S+$/u;

                    var form = document.querySelector('form[action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"]');
                    if (!form) return;

                    var pwd = form.querySelector('input[name="password"]');
                    var err = document.getElementById('password-error');

                    // Validation live (feedback instantané)
                    pwd.addEventListener('input', function() {
                        if (pwd.value === '') {
                            err.style.display = 'none';
                            err.textContent = '';
                            return;
                        }
                        try {
                            if (!pattern.test(pwd.value)) {
                                err.style.display = 'block';
                                err.textContent = 'Mot de passe non conforme : au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial. (pas d\'espaces)';
                            } else {
                                err.style.display = 'none';
                                err.textContent = '';
                            }
                        } catch (e) {
                            // En cas d'environnement JS ancien, désactiver la validation client
                            err.style.display = 'none';
                            err.textContent = '';
                        }
                    });

                    // Bloquer la soumission si la validation client échoue (UX)
                    form.addEventListener('submit', function(e) {
                        if (pwd && pwd.value && !pattern.test(pwd.value)) {
                            e.preventDefault();
                            err.style.display = 'block';
                            err.textContent = 'Mot de passe non conforme. Corrigez avant d\'envoyer.';
                            pwd.focus();
                            return false;
                        }
                    });
                })();
            </script>
        </div>
    </main>
</body>

</html>