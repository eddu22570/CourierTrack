<?php
require_once 'config.php';

// Vérifier si l'utilisateur est connecté et a les droits nécessaires
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrateur') {
    header("Location: login.php");
    exit;
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['password']; // Champ "password" du formulaire
    $role = $_POST['role'];

    // Vérifier que tous les champs obligatoires sont remplis
    if (empty($nom) || empty($email) || empty($mot_de_passe) || empty($role)) {
        die("Erreur : Tous les champs doivent être remplis.");
    }

    // Hacher le mot de passe avant de l'insérer dans la base de données
    $hashedPassword = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Connexion à la base de données
    $db = getDB();

    // Insérer l'utilisateur dans la table `users`
    try {
        $stmt = $db->prepare("INSERT INTO users (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $hashedPassword, $role]);

        // Rediriger vers la page de gestion des utilisateurs avec un message de succès
        $_SESSION['message'] = "Utilisateur ajouté avec succès.";
        header("Location: gestion_utilisateurs.php");
        exit;
    } catch (PDOException $e) {
        // Gérer les erreurs SQL (par exemple, email déjà utilisé)
        die("Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage());
    }
} else {
    // Si le formulaire n'a pas été soumis, rediriger vers la page d'ajout
    header("Location: ajouter_utilisateur.php");
    exit;
}
?>
