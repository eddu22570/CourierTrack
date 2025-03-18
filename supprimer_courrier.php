<?php
require_once 'config.php';

// Vérifier si l'utilisateur est connecté et a les droits nécessaires
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Administrateur' && $_SESSION['role'] !== 'accueil')) {
    header("Location: index.php");
    exit;
}

// Vérifier si un ID est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: liste_courriers.php");
    exit;
}

$id = intval($_GET['id']);

$db = getDB();

// Supprimer le courrier
$stmt = $db->prepare("DELETE FROM courriers WHERE id = ?");
$stmt->execute([$id]);

// Rediriger vers la liste des courriers avec un message de succès
$_SESSION['message'] = "Le courrier a été supprimé avec succès.";
header("Location: liste_courriers.php");
exit;
?>
