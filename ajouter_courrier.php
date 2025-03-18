<?php
require_once 'config.php';
require_once 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$db = getDB();

// Définir les constantes
define('TYPES_COURRIER', ['lettre', 'colis', 'recommande']);
define('STATUTS_COURRIER', ['recu', 'en_attente', 'distribue']);
define('DIRECTIONS', ['entrant', 'sortant']);

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_suivi = trim($_POST['numero_suivi']);
    $expediteur = trim($_POST['expediteur']);
    $type_destinataire = $_POST['type_destinataire'];
    $type = $_POST['type'];
    $statut = $_POST['statut'];
    $direction = $_POST['direction'];
    $ajoute_par = $_SESSION['user_id'];

    // Gestion du destinataire
    if ($type_destinataire === 'interne') {
        $destinataire_id = $_POST['destinataire_id'];
        $stmt = $db->prepare("SELECT nom FROM users WHERE id = ?");
        $stmt->execute([$destinataire_id]);
        $destinataire_nom = $stmt->fetchColumn();
    } else {
        $destinataire_id = null;
        $destinataire_nom = trim($_POST['destinataire_externe_nom']);
    }

    try {
        $stmt = $db->prepare("
            INSERT INTO courriers (
                numero_suivi, expediteur, destinataire, 
                destinataire_id, type_destinataire, type, 
                statut, direction, ajoute_par
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $numero_suivi, $expediteur, $destinataire_nom,
            $destinataire_id, $type_destinataire, $type,
            $statut, $direction, $ajoute_par
        ]);

        $message = "Courrier ajouté avec succès !";
    } catch(PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CourierTrack - Ajouter un courrier</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; }
        h1 { color: #333; }
        form { max-width: 500px; margin: 20px auto; }
        label { display: block; margin: 10px 0 5px; }
        input[type="text"], select { width: 100%; padding: 8px; margin-bottom: 10px; }
        input[type="submit"] { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        input[type="submit"]:hover { background-color: #45a049; }
        .error { color: red; }
        .success { color: green; }
    </style>
    <script>
    function toggleDestinataire() {
        const type = document.getElementById('type_destinataire').value;
        document.getElementById('destinataire_interne').style.display = 
            type === 'interne' ? 'block' : 'none';
        document.getElementById('destinataire_externe').style.display = 
            type === 'externe' ? 'block' : 'none';
    }
    </script>
</head>
<body>
    <h1>Ajouter un courrier</h1>

    <?php
    if ($message) echo "<p class='success'>$message</p>";
    if ($error) echo "<p class='error'>$error</p>";
    ?>

    <form method="post" action="ajouter_courrier.php">
        <label for="numero_suivi">Numéro de suivi :</label>
        <input type="text" id="numero_suivi" name="numero_suivi" required>

        <label for="expediteur">Expéditeur :</label>
        <input type="text" id="expediteur" name="expediteur" required>

        <label for="type_destinataire">Type de destinataire :</label>
        <select name="type_destinataire" id="type_destinataire" onchange="toggleDestinataire()" required>
            <option value="interne">Interne</option>
            <option value="externe">Externe</option>
        </select>

        <div id="destinataire_interne">
            <label for="destinataire_id">Destinataire interne :</label>
            <select name="destinataire_id" id="destinataire_id">
                <?php
                $stmt = $db->query("SELECT id, nom FROM users");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nom']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div id="destinataire_externe" style="display:none;">
            <label for="destinataire_externe_nom">Destinataire externe :</label>
            <input type="text" name="destinataire_externe_nom" id="destinataire_externe_nom">
        </div>

        <label for="type">Type de courrier :</label>
        <select id="type" name="type" required>
            <?php foreach (TYPES_COURRIER as $type): ?>
                <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars(ucfirst($type)) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="statut">Statut :</label>
        <select id="statut" name="statut" required>
            <?php foreach (STATUTS_COURRIER as $statut): ?>
                <option value="<?= htmlspecialchars($statut) ?>"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $statut))) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="direction">Direction :</label>
        <select id="direction" name="direction" required>
            <?php foreach (DIRECTIONS as $direction): ?>
                <option value="<?= htmlspecialchars($direction) ?>"><?= htmlspecialchars(ucfirst($direction)) ?></option>
            <?php endforeach; ?>
        </select>

        <input type="submit" value="Ajouter le courrier">
    </form>

    <a href="gestion_courriers.php">Retour à la gestion des courriers</a>
</body>
</html>
<?php require_once 'footer.php'; ?>
