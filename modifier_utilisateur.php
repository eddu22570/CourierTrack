<?php
require_once 'config.php';
require_once 'header.php'; // Inclusion de l'en-tête

// Vérification des droits d'accès
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrateur') {
    header("Location: login.php");
    exit;
}

// Récupération de l'ID de l'utilisateur à modifier
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$db = getDB();
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Erreur : Utilisateur non trouvé.");
}

// Mise à jour des données après soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Vérifier si un nouveau mot de passe a été fourni
    if (!empty($_POST['password'])) {
        $mot_de_passe = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET nom = ?, email = ?, mot_de_passe = ?, role = ? WHERE id = ?");
        $stmt->execute([$nom, $email, $mot_de_passe, $role, $id]);
    } else {
        $stmt = $db->prepare("UPDATE users SET nom = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$nom, $email, $role, $id]);
    }

    // Redirection après mise à jour
    $_SESSION['message'] = "Utilisateur modifié avec succès.";
    header("Location: gestion_utilisateurs.php");
    exit;
}
?>

<h1>Modifier l'utilisateur</h1>

<form method="POST">
    <label for="nom">Nom :</label><br>
    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required><br><br>

    <label for="email">Email :</label><br>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

    <label for="password">Nouveau mot de passe (laisser vide pour ne pas changer) :</label><br>
    <input type="password" id="password" name="password"><br><br>

    <label for="role">Rôle :</label><br>
    <select id="role" name="role">
        <option value="Utilisateur" <?= ($user['role'] === 'Utilisateur') ? 'selected' : '' ?>>Utilisateur</option>
        <option value="Administrateur" <?= ($user['role'] === 'Administrateur') ? 'selected' : '' ?>>Administrateur</option>
    </select><br><br>

    <button type="submit">Modifier l'utilisateur</button>
</form>

<a href="gestion_utilisateurs.php">Retour à la gestion des utilisateurs</a>
<?php require_once 'footer.php'; ?>
