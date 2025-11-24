<?php
// Fichier à inclure en haut de chaque page protégée
// Exemple : require __DIR__ . '/auth_check.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Sauvegarder l'URL demandée pour redirection après login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    // Rediriger vers la page de login
    header('Location: login.php');
    exit;
}

// Vérifier si la session n'a pas expiré (optionnel - 2 heures)
$session_timeout = 7200; // 2 heures en secondes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_timeout) {
    // Session expirée
    session_unset();
    session_destroy();
    header('Location: login.php?error=session_expired');
    exit;
}

// Mettre à jour le timestamp de dernière activité
$_SESSION['last_activity'] = time();

// Variables utilisateur disponibles après cette vérification :
// $_SESSION['user_id']
// $_SESSION['user_nom']
// $_SESSION['user_prenom']
// $_SESSION['user_email']
// $_SESSION['user_role']
?>