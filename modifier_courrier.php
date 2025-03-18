<?php
require_once 'config.php';
require_once 'header.php'; // Inclusion de l'en-tête

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$db = getDB();
$stmt = $db->prepare("SELECT * FROM courriers WHERE id = ?");
$stmt->execute([$id]);
$courrier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$courrier) {
    header("Location: liste_courriers.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_suivi = $_POST['numero_suivi'];
    $expediteur = $_POST['expediteur'];
    $destinataire = $_POST['destinataire'];
    $type = $_POST['type'];
    $statut = $_POST['statut'];

    $stmt = $db->prepare("
        UPDATE courriers SET numero_suivi = ?, expediteur = ?, destinataire = ?, type = ?, statut = ?
        WHERE id = ?
    ");
    
    try {
        $stmt->execute([$numero_suivi, $expediteur, $destinataire, $type, $statut, $id]);
        header("Location: liste_courriers.php");
        exit;
    } catch (PDOException $e) {
        $error = "Erreur lors de la modification du courrier : " . $e->getMessage();
    }
}
?>

<h2>Modifier le Courrier</h2>

<form method="POST">
    Numéro de Suivi :<br />
    <input type="text" name="numero_suivi" value="<?= htmlspecialchars($courrier['numero_suivi']) ?>" required><br /><br />

    Expéditeur :<br />
    <input type="text" name="expediteur" value="<?= htmlspecialchars($courrier['expediteur']) ?>" required><br /><br />

    Destinataire :<br />
    <input type="text" name="destinataire" value="<?= htmlspecialchars($courrier['destinataire']) ?>" required><br /><br />

    Type :<br />
    <select name="type">
        <option value="Courrier" <?= ($courrier['type'] === 'Courrier') ? 'selected' : '' ?>>Courrier</option>
        <option value="Colis" <?= ($courrier['type'] === 'Colis') ? 'selected' : '' ?>>Colis</option>
    </select><br /><br />

    Statut :<br />
    <select name="statut">
        <option value="En attente" <?= ($courrier['statut'] === 'En attente') ? 'selected' : '' ?>>En attente</option>
        <option value="En cours" <?= ($courrier['statut'] === 'En cours') ? 'selected' : '' ?>>En cours</option>
        <option value="Traité" <?= ($courrier['statut'] === 'Traité') ? 'selected' : '' ?>>Traité</option>
    </select><br /><br />

    <button type="submit">Modifier le courrier</button>
</form>

<a href="liste_courriers.php">Retour à la liste des courriers</a>

<?php require_once 'footer.php'; // Inclusion du pied de page ?>
