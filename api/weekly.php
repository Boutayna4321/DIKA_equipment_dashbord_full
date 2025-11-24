<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config.php';

$site = $_GET['site'] ?? 'GLOBAL';
$labels = [];
$data = [];

// Générer les 12 dernières semaines
for ($i = 11; $i >= 0; $i--) {
    $week_num = date('W', strtotime("-$i weeks"));
    $labels[] = "S$week_num";
}

// Construire la requête WHERE
$where = "YEARWEEK(date_debut, 3) >= YEARWEEK(DATE_SUB(CURDATE(), INTERVAL 11 WEEK), 3)";
if ($site !== 'GLOBAL') {
    $esc = $conn->real_escape_string($site);
    $where .= " AND usine='" . $esc . "'";
}

// Récupérer les données par semaine
$data = array_fill(0, 12, 0);
$res = $conn->query("
    SELECT YEARWEEK(date_debut, 3) as yw, COUNT(*) as c 
    FROM interventions 
    WHERE $where 
    GROUP BY yw 
    ORDER BY yw
");

// Remplir le tableau de données
$current_week = (int)date('W');
$current_year = (int)date('Y');

while ($r = $res->fetch_assoc()) {
    $year_week = $r['yw'];
    $year = (int)substr($year_week, 0, 4);
    $week = (int)substr($year_week, 4, 2);
    
    // Calculer la position dans le tableau (0-11)
    $weeks_diff = ($current_year - $year) * 52 + ($current_week - $week);
    $index = 11 - $weeks_diff;
    
    if ($index >= 0 && $index < 12) {
        $data[$index] = (int)$r['c'];
    }
}

echo json_encode(['labels' => $labels, 'data' => $data]);
$conn->close();
?>