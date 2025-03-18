<?php
define('DB_PATH', __DIR__ . '/bdd/courrier.db');

function getDB() {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

// Vérifie si aucune session n'est active avant de démarrer
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Administrateur';
}

function isAccueil() {
    return isset($_SESSION['role']) && ($_SESSION['role'] === 'Accueil' || $_SESSION['role'] === 'Administrateur');
}
