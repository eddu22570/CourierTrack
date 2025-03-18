<?php
require_once 'config.php';
require_once 'header.php';

// Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$db = getDB();

// Récupération des informations utilisateur
$user_id = $_SESSION['user_id'];
$is_admin = isAdmin();

// Construction de la requête SQL en fonction du rôle
if ($is_admin) {
    $sql = "
        SELECT c.*, u.nom AS destinataire_nom, ua.nom AS ajoute_par_nom
        FROM courriers c
        LEFT JOIN users u ON c.destinataire_id = u.id
        LEFT JOIN users ua ON c.ajoute_par = ua.id
    ";
    $stmt = $db->query($sql);
} else {
    $sql = "
        SELECT c.*, u.nom AS destinataire_nom, ua.nom AS ajoute_par_nom
        FROM courriers c
        LEFT JOIN users u ON c.destinataire_id = u.id
        LEFT JOIN users ua ON c.ajoute_par = ua.id
        WHERE c.ajoute_par = ?
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$user_id]);
}

$courriers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CourierTrack - Liste des courriers</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .action-links a {
            margin-right: 10px;
            text-decoration: none;
        }
        .action-links a.modify {
            color: blue;
        }
        .action-links a.delete {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Liste des courriers</h1>

    <?php if (empty($courriers)): ?>
        <p>Aucun courrier trouvé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Numéro de suivi</th>
                    <th>Expéditeur</th>
                    <th>Destinataire</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Direction</th>
                    <th>Date d'ajout</th>
                    <?php if ($is_admin): ?>
                        <th>Ajouté par</th>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courriers as $courrier): ?>
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
                        <td><?= htmlspecialchars($courrier['date_ajout']) ?></td>
                        <?php if ($is_admin): ?>
                            <td><?= htmlspecialchars($courrier['ajoute_par_nom']) ?></td>
                            <td class="action-links">
                                <a href="modifier_courrier.php?id=<?= $courrier['id'] ?>" class="modify">Modifier</a>
                                <a href="supprimer_courrier.php?id=<?= $courrier['id'] ?>" class="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce courrier ?');">Supprimer</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="ajouter_courrier.php" class="button">Ajouter un courrier</a>
    <a href="index.php" class="button">Retour à l'accueil</a>
</body>
</html>
<?php require_once 'footer.php'; ?>
