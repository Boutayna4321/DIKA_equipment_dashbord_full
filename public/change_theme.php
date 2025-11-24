<?php
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Récupérer le thème demandé
$theme = $_POST['theme'] ?? 'system';

// Valider le thème
$allowed_themes = ['light', 'dark', 'system'];
if (!in_array($theme, $allowed_themes)) {
    $theme = 'system';
}

// Enregistrer le thème en session
$_SESSION['theme'] = $theme;

// OPTIONNEL : Si vous voulez sauvegarder dans la BDD
// Décommentez les lignes ci-dessous après avoir ajouté la colonne 'theme' dans la table 'utilisateurs'
/*
require __DIR__ . '/../config.php';
$stmt = $conn->prepare("UPDATE utilisateurs SET theme = ? WHERE id = ?");
$stmt->bind_param("si", $theme, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();
$conn->close();
*/

http_response_code(200);
echo json_encode(['success' => true, 'theme' => $theme]);
?>