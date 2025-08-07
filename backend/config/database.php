<?php
// Configuration de la base de données
$host = 'localhost'; // Adresse du serveur de base de données
$dbname = 'nom_de_la_base_de_donnees'; // Nom de la base de données
$username = 'nom_utilisateur'; // Nom d'utilisateur de la base de données
$password = 'mot_de_passe'; // Mot de passe de la base de données

try {
    // Création d'une nouvelle connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configuration des attributs PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Gestion des erreurs de connexion
    echo "Erreur de connexion : " . $e->getMessage();
}
?>