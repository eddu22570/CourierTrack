<?php
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Récupérer le rôle et le nom d'utilisateur depuis la session
$role = $_SESSION['role'];
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Utilisateur';
//$username = $_SESSION['username']; // Assurez-vous que le nom d'utilisateur est stocké dans la session
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="header">
        <h1>Bienvenue, <?= htmlspecialchars($username) ?> !</h1>
        <p>Rôle : <?= htmlspecialchars($role) ?></p>

        <!-- Boutons pour les administrateurs -->
        <?php if ($role === 'Administrateur'): ?>
            <div class="admin-buttons">
                <a href="gestion_utilisateurs.php" class="button">Gérer les utilisateurs</a>
                <a href="ajouter_utilisateur.php" class="button">Ajouter un utilisateur</a>
            </div>
        <?php endif; ?>

        <!-- Boutons pour tous les utilisateurs -->
        <div class="general-buttons">
            <a href="ajouter_courrier.php" class="button">Ajouter un courrier</a>
            <a href="liste_courriers.php" class="button">Voir les courriers</a>
            <a href="a-propos.php" class="button">A propos du logiciel</a>
        </div>

        <!-- Bouton de déconnexion -->
        <div class="logout-button">
            <a href="logout.php" class="button">Se déconnecter</a>
        </div>
    </div>

    <!-- Contenu principal commence ici -->
    <div class="container">
