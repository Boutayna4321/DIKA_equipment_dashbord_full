<?php
session_start();

// Langues autorisées
$allowed_langs = ['fr', 'en', 'cn'];

// Récupérer la langue demandée
$lang = $_GET['lang'] ?? 'fr';

// Vérifier si la langue est valide
if (in_array($lang, $allowed_langs)) {
    $_SESSION['lang'] = $lang;
}

// Récupérer la page de redirection
$redirect = $_GET['redirect'] ?? 'index.php';

// Nettoyer la redirection pour éviter les injections
$redirect = basename($redirect);

// Si pas d'extension, ajouter .php
if (strpos($redirect, '.php') === false) {
    $redirect .= '.php';
}

// Rediriger vers la page demandée
header("Location: $redirect");
exit;
?>