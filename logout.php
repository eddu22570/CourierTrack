<?php
session_start(); // Démarrer la session

// Supprimer toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();

// Rediriger l'utilisateur vers la page d'accueil ou de connexion
header("Location: index.php");
exit;
?>
