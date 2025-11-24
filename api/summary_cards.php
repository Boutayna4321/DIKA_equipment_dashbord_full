<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config.php';

$site = $_GET['site'] ?? 'GLOBAL';
$cards = [];

// Build WHERE clause
$where_equip = '';
$where_pannes = "statut='en_cours'";
$where_interv = "statut='en_cours'";

if ($site !== 'GLOBAL') {
    $esc = $conn->real_escape_string($site);
    $where_equip = " WHERE usine='$esc'";
    $where_pannes .= " AND usine='$esc'";
    $where_interv .= " AND usine='$esc'";
}

// Total equipements
$cards['total_equipements'] = (int)$conn->query(
    "SELECT COUNT(*) c FROM equipements" . $where_equip
)->fetch_assoc()['c'];

// Pannes en cours
$cards['pannes_en_cours'] = (int)$conn->query(
    "SELECT COUNT(*) c FROM pannes WHERE $where_pannes"
)->fetch_assoc()['c'];

// Interventions en cours
$cards['interventions_en_cours'] = (int)$conn->query(
    "SELECT COUNT(*) c FROM interventions WHERE $where_interv"
)->fetch_assoc()['c'];

// Disponibilité
$dispo_query = "SELECT SUM(etat!='arrete') ok, COUNT(*) tot FROM equipements" . $where_equip;
$dispo_row = $conn->query($dispo_query)->fetch_assoc();
$cards['disponibilite'] = ($dispo_row && (int)$dispo_row['tot'] > 0) 
    ? round(($dispo_row['ok'] / $dispo_row['tot']) * 100, 2) 
    : 0;

// MTTR (Mean Time To Repair) - en heures
$mttr_where = "date_fin IS NOT NULL";
if ($site !== 'GLOBAL') {
    $mttr_where .= " AND usine='$esc'";
}

$mttr_query = "
    SELECT AVG(TIMESTAMPDIFF(HOUR, date_debut, date_fin)) as avg_hours 
    FROM interventions 
    WHERE $mttr_where 
    AND date_debut >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
";
$mttr_result = $conn->query($mttr_query)->fetch_assoc();
$cards['mttr'] = $mttr_result && $mttr_result['avg_hours'] 
    ? round($mttr_result['avg_hours'], 1) 
    : 0;

// MTBF (Mean Time Between Failures) - en jours
$mtbf_where = "";
if ($site !== 'GLOBAL') {
    $mtbf_where = " AND usine='$esc'";
}

$mtbf_query = "
    SELECT 
        COUNT(*) as total_pannes,
        DATEDIFF(MAX(date_debut), MIN(date_debut)) as days_span
    FROM pannes 
    WHERE date_debut >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)
    $mtbf_where
";
$mtbf_result = $conn->query($mtbf_query)->fetch_assoc();

if ($mtbf_result && $mtbf_result['total_pannes'] > 1) {
    $cards['mtbf'] = round($mtbf_result['days_span'] / $mtbf_result['total_pannes'], 1);
} else {
    $cards['mtbf'] = 0;
}

echo json_encode($cards);
$conn->close();
?>