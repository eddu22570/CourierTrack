<?php
require_once 'config.php';
require_once 'header.php';

// Vérifier les droits administrateur
if (!isAdmin()) {
    header("Location: index.php");
    exit;
}

$db = getDB();

// Traitement modification mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $user_id = $_POST['user_id'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $db->prepare("UPDATE users SET mot_de_passe = ? WHERE id = ?");
    if ($stmt->execute([$new_password, $user_id])) {
        $_SESSION['success'] = "Mot de passe modifié avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la modification du mot de passe.";
    }
}

// Traitement suppression utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    // Supprimer l'utilisateur
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$user_id])) {
        $_SESSION['success'] = "Utilisateur supprimé avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur.";
    }
}

// Récupérer tous les utilisateurs
$stmt = $db->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs - CourierTrack</title>
    <style>
        .user-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .user-table th, .user-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .action-form { display: inline-block; margin-right: 5px; }
        .password-form { background: #f5f5f5; padding: 15px; margin-top: 10px; }
        .success { color: green; margin-bottom: 15px; }
        .error { color: red; margin-bottom: 15px; }
        button { padding: 5px 10px; cursor: pointer; }
        button.delete { background-color: #ff4444; color: white; border: none; }
        button.modify { background-color: #007bff; color: white; border: none; }
    </style>
</head>
<body>
    <h1>Gestion des utilisateurs</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['nom']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                    <!-- Formulaire modification mot de passe -->
                    <form class="action-form" method="post" onsubmit="return confirm('Modifier le mot de passe de cet utilisateur ?')">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
                        <button type="submit" name="change_password" class="modify">Modifier MDP</button>
                    </form>

                    <!-- Formulaire suppression utilisateur -->
                    <form class="action-form" method="post" onsubmit="return confirm('Supprimer définitivement cet utilisateur ?')">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit" name="delete_user" class="delete">Supprimer</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
<?php require_once 'footer.php'; // Inclusion du pied de page ?>