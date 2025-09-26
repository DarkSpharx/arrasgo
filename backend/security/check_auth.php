<?php
// Contrôle d'accès : démarrer la session, vérifier l'authentification, sinon rediriger vers la connexion
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function check_auth()
{
    // Vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        // Redirige vers la page de connexion si non authentifié
        header('Location: /backoffice/login.php');
        exit;
    }
}

// Appel de la fonction pour vérifier l'authentification
check_auth();
