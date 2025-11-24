<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config.php';

$site = $_GET['site'] ?? 'GLOBAL';
$month_names = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
$labels = [];
$data = array_fill(0, 12, 0);

// Générer les labels des 12 derniers mois
for ($i = 11; $i >= 0; $i--) {
    $month_index = (int)date('n', strtotime("-$i months")) - 1;
    $labels[] = $month_names[$month_index];
}

// Construire la requête WHERE
$where = "date_debut >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)";
if ($site !== 'GLOBAL') {
    $esc = $conn->real_escape_string($site);
    $where .= " AND usine='" . $esc . "'";
}

// Récupérer les données par mois
$res = $conn->query("
    SELECT DATE_FORMAT(date_debut, '%Y-%m') as ym, COUNT(*) as c 
    FROM interventions 
    WHERE $where 
    GROUP BY ym 
    ORDER BY ym
");

// Remplir le tableau de données
$current_month = (int)date('n');
$current_year = (int)date('Y');

while ($r = $res->fetch_assoc()) {
    list($year, $month) = explode('-', $r['ym']);
    $year = (int)$year;
    $month = (int)$month;
    
    // Calculer la position dans le tableau (0-11)
    $months_diff = ($current_year - $year) * 12 + ($current_month - $month);
    $index = 11 - $months_diff;
    
    if ($index >= 0 && $index < 12) {
        $data[$index] = (int)$r['c'];
    }
}

echo json_encode(['labels' => $labels, 'data' => $data]);
$conn->close();
?>