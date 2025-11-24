<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../auth_check.php';
require_once __DIR__ . '/../config.php';

$site = $_GET['site'] ?? 'GLOBAL';

try {
    $sql = "SELECT 
                p.id,
                DATE_FORMAT(p.date_panne, '%d/%m/%Y %H:%i') as date,
                e.nom as equipement,
                e.site,
                p.gravite,
                p.description,
                CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM interventions i 
                        WHERE i.panne_id = p.id AND i.statut = 'termine'
                    ) THEN 'termine'
                    WHEN EXISTS (
                        SELECT 1 FROM interventions i 
                        WHERE i.panne_id = p.id AND i.statut = 'en_cours'
                    ) THEN 'en_cours'
                    ELSE 'planifie'
                END as statut,
                COALESCE(
                    (SELECT CONCAT(u.prenom, ' ', u.nom) 
                     FROM interventions i
                     LEFT JOIN utilisateurs u ON i.technicien_id = u.id
                     WHERE i.panne_id = p.id
                     ORDER BY i.date_intervention DESC
                     LIMIT 1),
                    '-'
                ) as technicien,
                COALESCE(
                    (SELECT CONCAT(
                        FLOOR(TIMESTAMPDIFF(MINUTE, i.date_debut, COALESCE(i.date_fin, NOW())) / 60), 'h ',
                        MOD(TIMESTAMPDIFF(MINUTE, i.date_debut, COALESCE(i.date_fin, NOW())), 60), 'min'
                    )
                     FROM interventions i
                     WHERE i.panne_id = p.id
                     ORDER BY i.date_intervention DESC
                     LIMIT 1),
                    '-'
                ) as duree,
                p.type_panne,
                e.code_equipement
            FROM pannes p
            LEFT JOIN equipements e ON p.equipement_id = e.id
            WHERE 1=1";
    
    if ($site !== 'GLOBAL') {
        $sql .= " AND e.site = :site";
    }
    
    $sql .= " ORDER BY p.date_panne DESC LIMIT 1000";
    
    $stmt = $pdo->prepare($sql);
    
    if ($site !== 'GLOBAL') {
        $stmt->bindParam(':site', $site);
    }
    
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($results);
    
} catch (Exception $e) {
    error_log("Error in pannes_list.php: " . $e->getMessage());
    echo json_encode([]);
}
?>