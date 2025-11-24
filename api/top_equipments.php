<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config.php';

$site = $_GET['site'] ?? 'GLOBAL';
$limit = $_GET['limit'] ?? 10;

// Build WHERE clause
$where = "1=1";
if ($site !== 'GLOBAL') {
    $esc = $conn->real_escape_string($site);
    $where = "p.usine='$esc'";
}

// Get top equipments by breakdown count (last 90 days)
$query = "
    SELECT 
        e.nom,
        e.reference,
        COUNT(p.id) as nb_pannes,
        SUM(CASE WHEN p.priorite = 'critique' THEN 1 ELSE 0 END) as nb_critiques,
        MAX(p.date_debut) as derniere_panne
    FROM equipements e
    INNER JOIN pannes p ON e.id = p.id_equipement
    WHERE $where
    AND p.date_debut >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)
    GROUP BY e.id, e.nom, e.reference
    ORDER BY nb_pannes DESC, nb_critiques DESC
    LIMIT " . (int)$limit;

$result = $conn->query($query);

$labels = [];
$data = [];
$details = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Truncate long names for chart display
        $name = strlen($row['nom']) > 25 
            ? substr($row['nom'], 0, 25) . '...' 
            : $row['nom'];
        
        $labels[] = $name;
        $data[] = (int)$row['nb_pannes'];
        $details[] = [
            'nom' => $row['nom'],
            'reference' => $row['reference'],
            'nb_pannes' => (int)$row['nb_pannes'],
            'nb_critiques' => (int)$row['nb_critiques'],
            'derniere_panne' => $row['derniere_panne']
        ];
    }
}

echo json_encode([
    'labels' => $labels,
    'data' => $data,
    'details' => $details
]);

$conn->close();
?>