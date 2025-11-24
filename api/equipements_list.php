<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../auth_check.php';
require_once __DIR__ . '/../config.php';

$site = $_GET['site'] ?? 'GLOBAL';

try {
    $sql = "SELECT 
                e.id,
                e.code_equipement as code,
                e.nom,
                e.site,
                COUNT(DISTINCT p.id) as nb_pannes,
                DATE_FORMAT(MAX(p.date_panne), '%d/%m/%Y') as derniere_panne,
                ROUND(
                    (SELECT COUNT(*) 
                     FROM interventions i2 
                     LEFT JOIN pannes p2 ON i2.panne_id = p2.id 
                     WHERE p2.equipement_id = e.id AND i2.statut = 'termine'
                    ) * 100.0 / NULLIF(COUNT(DISTINCT p.id), 0), 
                    1
                ) as disponibilite,
                COALESCE(
                    ROUND(
                        AVG(TIMESTAMPDIFF(DAY, p.date_panne, 
                            (SELECT MIN(p3.date_panne) 
                             FROM pannes p3 
                             WHERE p3.equipement_id = e.id 
                             AND p3.date_panne > p.date_panne)
                        )), 
                        0
                    ), 
                    0
                ) as mtbf,
                COALESCE(
                    ROUND(
                        AVG(TIMESTAMPDIFF(HOUR, i.date_debut, i.date_fin)), 
                        1
                    ), 
                    0
                ) as mttr,
                e.description,
                e.localisation
            FROM equipements e
            LEFT JOIN pannes p ON e.id = p.equipement_id
            LEFT JOIN interventions i ON p.id = i.panne_id AND i.statut = 'termine'
            WHERE 1=1";
    
    if ($site !== 'GLOBAL') {
        $sql .= " AND e.site = :site";
    }
    
    $sql .= " GROUP BY e.id, e.code_equipement, e.nom, e.site, e.description, e.localisation
              ORDER BY nb_pannes DESC, e.nom ASC";
    
    $stmt = $pdo->prepare($sql);
    
    if ($site !== 'GLOBAL') {
        $stmt->bindParam(':site', $site);
    }
    
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format data
    foreach ($results as &$row) {
        $row['disponibilite'] = $row['disponibilite'] ?? 0;
        $row['mtbf'] = $row['mtbf'] ?? 0;
        $row['mttr'] = $row['mttr'] ?? 0;
        $row['nb_pannes'] = $row['nb_pannes'] ?? 0;
        $row['derniere_panne'] = $row['derniere_panne'] ?? '-';
    }
    
    echo json_encode($results);
    
} catch (Exception $e) {
    error_log("Error in equipements_list.php: " . $e->getMessage());
    echo json_encode([]);
}
?>