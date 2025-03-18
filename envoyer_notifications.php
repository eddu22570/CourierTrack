<?php
require_once 'config.php';
require_once 'header.php'; // Inclusion de l'en-tête

$db = getDB();
$stmt = $db->query("SELECT c.id, c.numero_suivi, u.email 
                    FROM courriers c 
                    JOIN users u ON c.destinataire_id = u.id 
                    WHERE c.notifie = 0 AND c.statut = 'recu'");

while ($courrier = $stmt->fetch()) {
    envoyerNotification($courrier['email'], $courrier['numero_suivi']);
    $db->exec("UPDATE courriers SET notifie = 1 WHERE id = " . $courrier['id']);
    echo "Notification envoyée pour le courrier " . $courrier['numero_suivi'] . "\n";
}

function envoyerNotification($email, $numeroSuivi) {
    $sujet = "Votre courrier est arrivé";
    $message = "Bonjour,\n\nVotre courrier (numéro : $numeroSuivi) est arrivé et est disponible pour récupération.\n\nCordialement,\nLe service courrier";
    mail($email, $sujet, $message);
}

echo "Processus de notification terminé.\n";
?>