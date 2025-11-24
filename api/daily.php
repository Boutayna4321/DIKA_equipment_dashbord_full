<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config.php';

$site = $_GET['site'] ?? 'GLOBAL';
$days = $_GET['days'] ?? 7; // Par défaut 7 jours

// Build WHERE clause
$where = "date_debut >= DATE_SUB(CURDATE(), INTERVAL " . (int)$days . " DAY)";
if ($site !== 'GLOBAL') {
    $esc = $conn->real_escape_string($site);
    $where .= " AND usine='" . $esc . "'";
}

// Generate labels
$labels = [];
$data = array_fill(0, (int)$days, 0);

for ($i = (int)$days - 1; $i >= 0; $i--) {
    $date = date('d/m', strtotime("-$i days"));
    $labels[] = $date;
}

// Get data
$query = "
    SELECT 
        DATE(date_debut) as jour,
        COUNT(*) as c 
    FROM interventions 
    WHERE $where 
    GROUP BY DATE(date_debut)
    ORDER BY jour
";

$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    $jour = $row['jour'];
    $count = (int)$row['c'];
    
    // Find position in array
    $diff = floor((strtotime(date('Y-m-d')) - strtotime($jour)) / (60 * 60 * 24));
    $index = (int)$days - 1 - $diff;
    
    if ($index >= 0 && $index < (int)$days) {
        $data[$index] = $count;
    }
}

echo json_encode([
    'labels' => $labels,
    'data' => $data
]);

$conn->close();
?>