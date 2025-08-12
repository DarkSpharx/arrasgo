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

    // Récupérer les informations du formulaire
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Préparer la requête pour vérifier les informations d'identification
    $sql = "SELECT * FROM users_admins WHERE email_user = :username";

    if ($stmt = $pdo->prepare($sql)) {
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
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <title>Connexion Admin</title>
</head>

<body>
    <div class="login-container">
        <h2>Connexion Administrateur</h2>
        <?php if (isset($login_error)) {
            echo '<p class="error">' . $login_error . '</p>';
        } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>

</html>