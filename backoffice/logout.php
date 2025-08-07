<?php
session_start();

// Détruire la session de l'utilisateur
session_unset();
session_destroy();

// Rediriger vers la page de connexion
header("Location: login.php");
exit();
?>