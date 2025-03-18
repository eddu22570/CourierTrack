<?php
require_once 'config.php';
require_once 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$db = getDB();

// Construire la requête selon le rôle
if (isAdmin() || isAccueil()) {
    $stmt = $db->query("
        SELECT c.*, u.nom as destinataire_nom 
        FROM courriers c
        LEFT JOIN users u ON c.destinataire_id = u.id
    ");
} else {
    $stmt = $db->prepare("
        SELECT c.*, u.nom as destinataire_nom 
        FROM courriers c
        LEFT JOIN users u ON c.destinataire_id = u.id
        WHERE c.ajoute_par = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
}

$courriers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CourierTrack - Gestion des courriers</title>
    <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 8px; }
    th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Gestion des courriers</h1>

    <table>
        <thead>
            <tr>
                <th>N° Suivi</th>
                <th>Expéditeur</th>
                <th>Destinataire</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Direction</th>
                <?php if(isAdmin()): ?>
                    <th>Ajouté par</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($courriers as $courrier): ?>
                <tr>
                    <td><?= htmlspecialchars($courrier['numero_suivi']) ?></td>
                    <td><?= htmlspecialchars($courrier['expediteur']) ?></td>
                    <td>
                        <?= $courrier['type_destinataire'] === 'interne' 
                            ? htmlspecialchars($courrier['destinataire_nom'])
                            : htmlspecialchars($courrier['destinataire'])
                        ?>
                    </td>
                    <td><?= htmlspecialchars(ucfirst($courrier['type'])) ?></td>
                    <td><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $courrier['statut']))) ?></td>
                    <td><?= htmlspecialchars(ucfirst($courrier['direction'])) ?></td>
                    <?php if(isAdmin()): ?>
                        <td><?= htmlspecialchars($courrier['ajoute_par']) ?></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
<?php require_once 'footer.php'; ?>
