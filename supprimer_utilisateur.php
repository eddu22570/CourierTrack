<?php
require_once 'config.php';
require_once 'header.php'; // Inclusion de l'en-tête


// Vérification des droits d'accès
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$db = getDB();
$stmt = $db->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);

header("Location: gestion_utilisateurs.php");
exit;
?>
