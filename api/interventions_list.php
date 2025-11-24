<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config.php';

$site = $_GET['site'] ?? 'GLOBAL';
$limit = $_GET['limit'] ?? 100;

// Build WHERE clause
$where = "1=1";
if ($site !== 'GLOBAL') {
    $esc = $conn->real_escape_string($site);
    $where = "i.usine='$esc'";
}

// Query to get interventions with related data
$query = "
    SELECT 
        i.id,
        i.date_debut as date,
        i.usine as site,
        e.nom as equipement,
        e.reference,
        p.type_panne,
        p.priorite,
        i.statut,
        CASE 
            WHEN i.date_fin IS NOT NULL THEN 
                CONCAT(
                    TIMESTAMPDIFF(HOUR, i.date_debut, i.date_fin), 'h ',
                    MOD(TIMESTAMPDIFF(MINUTE, i.date_debut, i.date_fin), 60), 'min'
                )
            ELSE 'En cours'
        END as duree,
        CONCAT(u.prenom, ' ', u.nom) as technicien,
        i.type as type_intervention,
        i.importance,
        DATE_FORMAT(i.date_debut, '%d/%m/%Y %H:%i') as date_formatted
    FROM interventions i
    LEFT JOIN pannes p ON i.id_panne = p.id
    LEFT JOIN equipements e ON p.id_equipement = e.id
    LEFT JOIN utilisateurs u ON i.id_technicien = u.id
    WHERE $where
    ORDER BY i.date_debut DESC
    LIMIT " . (int)$limit;

$result = $conn->query($query);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => (int)$row['id'],
            'date' => $row['date_formatted'],
            'site' => $row['site'],
            'equipement' => $row['equipement'] ?: 'Maintenance préventive',
            'reference' => $row['reference'] ?: '-',
            'type_panne' => $row['type_panne'] ?: 'Préventive',
            'priorite' => $row['priorite'] ?: 'normale',
            'statut' => $row['statut'] ?: 'en_cours',
            'duree' => $row['duree'],
            'technicien' => $row['technicien'] ?: 'Non assigné',
            'type_intervention' => $row['type_intervention'],
            'importance' => $row['importance']
        ];
    }
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
$conn->close();
?>