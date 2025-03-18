<?php
require_once 'config.php';
require_once 'header.php'; // Inclusion de l'en-tête

// Vérification des droits d'accès (optionnel)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrateur') {
    header("Location: login.php");
    exit;
}
?>

<h1>Ajouter un utilisateur</h1>

<form method="POST" action="traiter_utilisateur.php">
    <label for="nom">Nom :</label><br>
    <input type="text" id="nom" name="nom"><br><br>

    <label for="email">Email :</label><br>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Mot de passe :</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <label for="role">Rôle :</label><br>
    <select id="role" name="role">
        <option value="Utilisateur">Utilisateur</option>
        <option value="Administrateur">Administrateur</option>
    </select><br><br>

    <button type="submit">Créer l'utilisateur</button>
</form>

<a href="gestion_utilisateurs.php">Retour à la gestion des utilisateurs</a>

<?php require_once 'footer.php'; // Inclusion du pied de page ?>
