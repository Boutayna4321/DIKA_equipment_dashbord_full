<?php
// Protection de la page
require __DIR__ . '/auth_check.php';

$selected = $_SESSION['lang'] ?? 'fr';
require __DIR__ . '/../lang.php';
$t = $lang[$selected] ?? $lang['fr'];

$user_name = $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'];
$user_role = $_SESSION['user_role'];
$theme = $_SESSION['theme'] ?? 'system';

require __DIR__ . '/../config.php';

$message = '';
$message_type = '';

// Suppression
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM pannes WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = $t['breakdown_deleted'];
        $message_type = "success";
    }
    $stmt->close();
}

// Ajout ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $id_equipement = intval($_POST['id_equipement']);
    $type_panne = $_POST['type_panne'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'] ?: null;
    $priorite = $_POST['priorite'];
    $statut = $_POST['statut'];
    $usine = $_POST['usine'];
    
    if ($id) {
        $stmt = $conn->prepare("UPDATE pannes SET id_equipement=?, type_panne=?, description=?, date_debut=?, date_fin=?, priorite=?, statut=?, usine=? WHERE id=?");
        $stmt->bind_param("isssssssi", $id_equipement, $type_panne, $description, $date_debut, $date_fin, $priorite, $statut, $usine, $id);
        $success_msg = $t['breakdown_modified'];
    } else {
        $stmt = $conn->prepare("INSERT INTO pannes (id_equipement, type_panne, description, date_debut, date_fin, priorite, statut, usine) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $id_equipement, $type_panne, $description, $date_debut, $date_fin, $priorite, $statut, $usine);
        $success_msg = $t['breakdown_added'];
    }
    
    if ($stmt->execute()) {
        $message = $success_msg;
        $message_type = "success";
    }
    $stmt->close();
}

// Récupération des équipements
$equipements = [];
$equip_result = $conn->query("SELECT id, nom FROM equipements ORDER BY nom");
while ($row = $equip_result->fetch_assoc()) {
    $equipements[] = $row;
}

// Paramètres de tri
$sort_by = $_GET['sort'] ?? 'id';
$sort_order = $_GET['order'] ?? 'DESC';
$allowed_columns = ['id', 'equipement', 'type_panne', 'date_debut', 'date_fin', 'priorite', 'statut', 'usine'];

if (!in_array($sort_by, $allowed_columns)) {
    $sort_by = 'id';
}
if (!in_array($sort_order, ['ASC', 'DESC'])) {
    $sort_order = 'DESC';
}

// Récupération des pannes avec filtres et tri
$pannes = [];
$filter_usine = $_GET['filter'] ?? 'all';
$filter_priorite = $_GET['priorite'] ?? 'all';
$filter_statut = $_GET['statut'] ?? 'all';

$query = "SELECT p.*, e.nom AS equipement FROM pannes p 
          JOIN equipements e ON e.id = p.id_equipement WHERE 1=1";

if ($filter_usine !== 'all') {
    $query .= " AND p.usine = '" . $conn->real_escape_string($filter_usine) . "'";
}

if ($filter_priorite !== 'all') {
    $query .= " AND p.priorite = '" . $conn->real_escape_string($filter_priorite) . "'";
}

if ($filter_statut !== 'all') {
    $query .= " AND p.statut = '" . $conn->real_escape_string($filter_statut) . "'";
}

// Gestion du tri
$sort_column = 'p.id';
if ($sort_by === 'equipement') {
    $sort_column = 'e.nom';
} elseif ($sort_by === 'type_panne') {
    $sort_column = 'p.type_panne';
} elseif ($sort_by === 'date_debut') {
    $sort_column = 'p.date_debut';
} elseif ($sort_by === 'date_fin') {
    $sort_column = 'p.date_fin';
} elseif ($sort_by === 'priorite') {
    $sort_column = 'p.priorite';
} elseif ($sort_by === 'statut') {
    $sort_column = 'p.statut';
} elseif ($sort_by === 'usine') {
    $sort_column = 'p.usine';
}

$query .= " ORDER BY $sort_column $sort_order";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    $pannes[] = $row;
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($selected) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['breakdown_management']) ?> - DIKA MAROCCO AFRICA</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="/DIKA_equipment_dashbord_full/public/images/favicon.png">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Nouvelle Palette - Thème Blanc Professionnel */
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --primary-light: #3b82f6;
            --secondary: #64748b;
            --accent: #0891b2;
            
            /* Couleurs Sémantiques */
            --danger: #dc2626;
            --danger-hover: #b91c1c;
            --warning: #f59e0b;
            --success: #059669;
            --info: #0284c7;
            
            /* Couleurs UI - Mode Light (Thème Blanc) */
            --bg-overlay: rgba(255, 255, 255, 0.95);
            --header-bg-start: #ffffff;
            --header-bg-end: #f8fafc;
            --sidebar-bg: rgba(248, 250, 252, 0.98);
            --sidebar-hover: rgba(37, 99, 235, 0.08);
            --sidebar-active: rgba(37, 99, 235, 0.15);
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: rgba(203, 213, 225, 0.8);
            --hover-bg: rgba(37, 99, 235, 0.05);
            --active-bg: rgba(37, 99, 235, 0.1);
            
            /* Couleurs pour les en-têtes de table */
            --table-header-bg: rgba(37, 99, 235, 0.1);
            --table-header-hover: rgba(37, 99, 235, 0.2);
            --table-header-text: #1e293b;
        }
        
        [data-theme="dark"] {
            /* Mode Dark amélioré */
            --bg-overlay: rgba(15, 23, 42, 0.95);
            --header-bg-start: #1e293b;
            --header-bg-end: #0f172a;
            --sidebar-bg: rgba(30, 41, 59, 0.95);
            --sidebar-hover: rgba(37, 99, 235, 0.15);
            --sidebar-active: rgba(37, 99, 235, 0.25);
            --card-bg: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-color: rgba(100, 116, 139, 0.4);
            --hover-bg: rgba(37, 99, 235, 0.15);
            --active-bg: rgba(37, 99, 235, 0.25);
            
            /* Couleurs pour les en-têtes de table en mode sombre */
            --table-header-bg: rgba(30, 58, 138, 0.4);
            --table-header-hover: rgba(37, 99, 235, 0.3);
            --table-header-text: #f1f5f9;
        }

        [data-theme="dark"] body {
            background: #0f172a;
        }

        [data-theme="dark"] .logo-header {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--border-color);
        }

        [data-theme="dark"] .filter-select,
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background: #1e293b;
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        [data-theme="dark"] .filter-select option,
        [data-theme="dark"] .form-select option {
            background: #1e293b;
            color: var(--text-primary);
        }

        [data-theme="dark"] .modal-content {
            background: #1e293b;
            color: var(--text-primary);
        }

        [data-theme="dark"] .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        [data-theme="dark"] .modal-body {
            background: #1e293b;
        }

        /* CORRECTION DU TABLEAU EN MODE SOMBRE */
        [data-theme="dark"] .data-table {
            background: transparent;
            color: var(--text-primary);
        }

        [data-theme="dark"] .data-table thead {
            background: var(--table-header-bg);
        }

        [data-theme="dark"] .data-table th {
            color: var(--table-header-text);
            border-bottom: 2px solid var(--primary);
        }

        [data-theme="dark"] .data-table tbody tr {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
        }

        [data-theme="dark"] .data-table tbody tr:hover {
            background: var(--hover-bg);
        }

        [data-theme="dark"] .data-table td {
            color: var(--text-secondary);
            background: transparent;
        }

        [data-theme="dark"] .table-container {
            background: #1e293b;
            border: 1px solid var(--border-color);
        }

        [data-theme="dark"] .breadcrumb-custom {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            color: var(--text-primary);
        }

        [data-theme="dark"] .breadcrumb-custom a {
            color: var(--text-secondary);
        }

        [data-theme="dark"] .breadcrumb-custom a:hover {
            color: var(--primary);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            background: url('images/bg-usine.jpeg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            overflow-x: hidden;
            color: var(--text-primary);
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--bg-overlay);
            z-index: 0;
        }

        .dashboard-wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            min-height: 100vh;
        }

        .top-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: linear-gradient(135deg, var(--header-bg-start) 0%, var(--header-bg-end) 100%);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 1000;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .menu-toggle {
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 24px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .menu-toggle:hover {
            background: var(--hover-bg);
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-header {
            width: 45px;
            height: 45px;
            background: white;
            border-radius: 8px;
            padding: 5px;
            border: 1px solid var(--border-color);
        }

        .logo-header img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-text-header h1 {
            color: var(--text-primary);
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .brand-text-header p {
            color: var(--text-secondary);
            font-size: 12px;
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .theme-selector, .lang-switch, .user-section {
            display: flex;
            gap: 5px;
            background: var(--hover-bg);
            padding: 5px;
            border-radius: 8px;
            align-items: center;
            border: 1px solid var(--border-color);
        }

        .theme-btn, .lang-switch a {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            padding: 6px 12px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .theme-btn.active, .theme-btn:hover, .lang-switch a.active, .lang-switch a:hover {
            background: var(--primary);
            color: white;
        }

        .user-info-header {
            text-align: right;
        }

        .user-name-header {
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 600;
        }

        .user-role-header {
            color: var(--text-secondary);
            font-size: 11px;
            text-transform: uppercase;
        }

        .logout-btn-header {
            background: var(--danger);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn-header:hover {
            background: var(--danger-hover);
            transform: translateY(-2px);
            color: white;
        }

        .sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            width: 280px;
            height: calc(100vh - 70px);
            background: var(--sidebar-bg);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--border-color);
            transition: all 0.3s ease;
            overflow-y: auto;
            z-index: 999;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.05);
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
            margin: 0;
        }

        .menu-item {
            margin: 5px 0;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 25px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            position: relative;
        }

        .menu-link:hover {
            background: var(--sidebar-hover);
            color: var(--primary);
            border-left-color: var(--primary-light);
        }

        .menu-link.active {
            background: var(--sidebar-active);
            color: var(--primary);
            border-left: 3px solid var(--primary);
            font-weight: 600;
        }

        .menu-icon {
            font-size: 20px;
            width: 24px;
            text-align: center;
            transition: transform 0.2s ease;
        }

        .menu-link:hover .menu-icon {
            transform: scale(1.1);
        }

        .menu-text {
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .menu-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .menu-separator {
            height: 1px;
            background: var(--border-color);
            margin: 15px 25px;
            opacity: 0.6;
        }

        .main-content {
            margin-left: 280px;
            margin-top: 70px;
            padding: 30px;
            width: calc(100% - 280px);
            transition: all 0.3s ease;
            background: transparent;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        .breadcrumb-custom {
            position: sticky;
            top: 70px;
            background: var(--hover-bg);
            padding: 12px 20px;
            margin-bottom: 25px;
            color: var(--text-primary);
            font-size: 14px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            z-index: 100;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .breadcrumb-custom a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb-custom a:hover {
            color: var(--primary);
        }

        .action-bar {
            position: sticky;
            top: calc(70px + 60px);
            background: var(--card-bg);
            padding: 20px 25px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            border: 1px solid var(--border-color);
            z-index: 90;
            backdrop-filter: blur(10px);
        }

        .btn-add {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .filter-group {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            background: var(--card-bg);
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .table-container {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
            max-height: 600px;
            overflow-y: auto;
            position: relative;
        }

        /* STYLES DU TABLEAU CORRIGÉS POUR LES DEUX THÈMES */
        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
            background: var(--card-bg);
            color: var(--text-primary);
        }

        .data-table thead {
            background: var(--table-header-bg);
            position: sticky;
            top: 0;
            transition: background-color 0.3s ease;
        }

        .data-table thead:hover {
            background: var(--table-header-hover);
        }

        .data-table th {
            padding: 14px 12px;
            text-align: left;
            color: var(--table-header-text);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 2px solid var(--primary);
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
            user-select: none;
        }

        .data-table th:hover {
            background: var(--table-header-hover);
        }

        .data-table th::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-dark);
            transition: width 0.3s ease;
        }

        .data-table th:hover::after {
            width: 100%;
        }

        .data-table th .sort-icon {
            margin-left: 5px;
            font-size: 10px;
            opacity: 0.5;
        }

        .data-table th.sorted .sort-icon {
            opacity: 1;
        }

        .data-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 13px;
            transition: background-color 0.2s ease;
        }

        .data-table tbody tr {
            transition: all 0.2s ease;
        }

        .data-table tbody tr:hover {
            background: var(--hover-bg);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-success {
            background: rgba(5, 150, 105, 0.15);
            color: #047857;
            border: 1px solid rgba(5, 150, 105, 0.3);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.15);
            color: #b45309;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-danger {
            background: rgba(220, 38, 38, 0.15);
            color: #b91c1c;
            border: 1px solid rgba(220, 38, 38, 0.3);
        }

        .badge-basse { background: rgba(5, 150, 105, 0.15); color: #047857; border: 1px solid rgba(5, 150, 105, 0.3); }
        .badge-moyenne { background: rgba(245, 158, 11, 0.15); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.3); }
        .badge-haute { background: rgba(255, 152, 0, 0.15); color: #e65100; border: 1px solid rgba(255, 152, 0, 0.3); }
        .badge-critique { background: rgba(220, 38, 38, 0.15); color: #b91c1c; border: 1px solid rgba(220, 38, 38, 0.3); }
        
        .badge-en_cours { background: rgba(37, 99, 235, 0.15); color: #1e40af; border: 1px solid rgba(37, 99, 235, 0.3); }
        .badge-terminee { background: rgba(5, 150, 105, 0.15); color: #047857; border: 1px solid rgba(5, 150, 105, 0.3); }
        .badge-en_attente { background: rgba(100, 116, 139, 0.15); color: #475569; border: 1px solid rgba(100, 116, 139, 0.3); }

        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0 3px;
            color: white;
        }

        .btn-edit {
            background: var(--primary);
        }

        .btn-edit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-delete {
            background: var(--danger);
        }

        .btn-delete:hover {
            background: var(--danger-hover);
            transform: translateY(-2px);
        }

        .pagination-info {
            background: var(--card-bg);
            padding: 15px 20px;
            margin-top: 15px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .pagination-info span {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .btn-load-more {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-load-more:hover {
            background: var(--primary-dark);
        }

        .btn-load-more:disabled {
            background: var(--text-secondary);
            cursor: not-allowed;
            opacity: 0.5;
        }

        .modal-content {
            background: var(--card-bg);
            border-radius: 12px;
            border: none;
        }

        .modal-content {
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            border: none;
        }

        .modal-title {
            font-weight: 700;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 10px 15px;
            font-size: 14px;
            background: var(--card-bg);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: rgba(5, 150, 105, 0.15);
            color: #047857;
            border: 1px solid rgba(5, 150, 105, 0.3);
        }

        .alert-danger {
            background: rgba(220, 38, 38, 0.15);
            color: #b91c1c;
            border: 1px solid rgba(220, 38, 38, 0.3);
        }

        /* Scrollbar personnalisé pour la table */
        .table-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: var(--hover-bg);
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .brand-text-header {
                display: none;
            }

            .user-info-header {
                display: none;
            }

            .table-container {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body data-theme="<?= $theme ?>">
    <div class="dashboard-wrapper">
        <!-- Header -->
        <header class="top-header">
            <div class="header-left">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div class="brand-header">
                    <div class="logo-header">
                        <img src="images/logo.jpeg" alt="DIKA Logo">
                    </div>
                    <div class="brand-text-header">
                        <h1><?= htmlspecialchars($t['brand']) ?></h1>
                        <p><?= htmlspecialchars($t['dept']) ?></p>
                    </div>
                </div>
            </div>

            <div class="header-right">
                <div class="theme-selector">
                    <button class="theme-btn <?= $theme === 'light' ? 'active' : '' ?>" onclick="changeTheme('light')">
                        <i class="bi bi-sun-fill"></i>
                    </button>
                    <button class="theme-btn <?= $theme === 'dark' ? 'active' : '' ?>" onclick="changeTheme('dark')">
                        <i class="bi bi-moon-fill"></i>
                    </button>
                    <button class="theme-btn <?= $theme === 'system' ? 'active' : '' ?>" onclick="changeTheme('system')">
                        <i class="bi bi-circle-half"></i>
                    </button>
                </div>

                <div class="lang-switch">
                    <a href="change_lang.php?lang=fr&redirect=pannes_admin" class="<?= $selected === 'fr' ? 'active' : '' ?>">FR</a>
                    <a href="change_lang.php?lang=en&redirect=pannes_admin" class="<?= $selected === 'en' ? 'active' : '' ?>">EN</a>
                    <a href="change_lang.php?lang=cn&redirect=pannes_admin" class="<?= $selected === 'cn' ? 'active' : '' ?>">中文</a>
                </div>

                <div class="user-section">
                    <div class="user-info-header">
                        <span class="user-name-header"><?= htmlspecialchars($user_name) ?></span>
                        <span class="user-role-header"><?= htmlspecialchars($user_role) ?></span>
                    </div>
                    <a href="logout.php" class="logout-btn-header"><?= htmlspecialchars($t['logout']) ?></a>
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar-menu">
                <li class="menu-item">
                    <a href="dashboard.php?site=GLOBAL" class="menu-link">
                        <span class="menu-icon"><i class="fa-solid fa-chart-column"></i></span>
                        <span class="menu-text">Global</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="dashboard.php?site=DMA1" class="menu-link">
                        <span class="menu-icon"><i class="fa-solid fa-industry"></i></span>
                        <span class="menu-text">DMA1</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="dashboard.php?site=DMA2" class="menu-link">
                        <span class="menu-icon"><i class="fa-solid fa-industry"></i></span>
                        <span class="menu-text">DMA2</span>
                    </a>
                </li>

                <div class="menu-separator"></div>

                <li class="menu-item">
                    <a href="equipements_admin.php" class="menu-link">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text"><?= htmlspecialchars($t['manage_equipment'] ?? 'Équipements') ?></span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="pannes_admin.php" class="menu-link active">
                        <span class="menu-icon"><i class="fa-solid fa-wrench"></i></span>
                        <span class="menu-text"><?= htmlspecialchars($t['manage_breakdowns'] ?? 'Pannes') ?></span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="interventions_admin.php" class="menu-link">
                        <span class="menu-icon"><i class="fa-solid fa-screwdriver-wrench"></i></span>
                        <span class="menu-text"><?= htmlspecialchars($t['manage_interventions'] ?? 'Interventions') ?></span>
                    </a>
                </li>
                
                <?php if ($user_role === 'admin'): ?>
                <li class="menu-item">
                    <a href="utilisateurs_admin.php" class="menu-link">
                        <span class="menu-icon"><i class="fa-solid fa-users"></i></span>
                        <span class="menu-text"><?= htmlspecialchars($t['manage_users'] ?? 'Utilisateurs') ?></span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="breadcrumb-custom">
                <i class="bi bi-house-door"></i> <a href="dashboard.php"><?= htmlspecialchars($t['home'] ?? 'Accueil') ?></a> > <?= htmlspecialchars($t['breakdown_management'] ?? 'Gestion des Pannes') ?>
            </div>

            <?php if ($message): ?>
            <div class="alert alert-<?= $message_type ?> alert-dismissible fade show">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Action Bar -->
            <div class="action-bar">
                <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalPanne" onclick="resetForm()">
                    <i class="bi bi-plus-circle"></i>
                    <?= htmlspecialchars($t['add_breakdown']) ?>
                </button>

                <div class="filter-group">
                    <label style="color: var(--text-primary); font-weight: 600;"><?= htmlspecialchars($t['filter']) ?>:</label>
                    <select class="filter-select" onchange="updateFilters('filter', this.value)">
                        <option value="all" <?= $filter_usine === 'all' ? 'selected' : '' ?>><?= htmlspecialchars($t['all_factories']) ?></option>
                        <option value="DMA1" <?= $filter_usine === 'DMA1' ? 'selected' : '' ?>>DMA1</option>
                        <option value="DMA2" <?= $filter_usine === 'DMA2' ? 'selected' : '' ?>>DMA2</option>
                    </select>
                    <select class="filter-select" onchange="updateFilters('priorite', this.value)">
                        <option value="all" <?= $filter_priorite === 'all' ? 'selected' : '' ?>><?= htmlspecialchars($t['all_priorities']) ?></option>
                        <option value="basse" <?= $filter_priorite === 'basse' ? 'selected' : '' ?>><?= htmlspecialchars($t['low']) ?></option>
                        <option value="moyenne" <?= $filter_priorite === 'moyenne' ? 'selected' : '' ?>><?= htmlspecialchars($t['medium']) ?></option>
                        <option value="haute" <?= $filter_priorite === 'haute' ? 'selected' : '' ?>><?= htmlspecialchars($t['high']) ?></option>
                        <option value="critique" <?= $filter_priorite === 'critique' ? 'selected' : '' ?>><?= htmlspecialchars($t['critical']) ?></option>
                    </select>
                    <select class="filter-select" onchange="updateFilters('statut', this.value)">
                        <option value="all" <?= $filter_statut === 'all' ? 'selected' : '' ?>><?= htmlspecialchars($t['all_statuses']) ?></option>
                        <option value="en_cours" <?= $filter_statut === 'en_cours' ? 'selected' : '' ?>><?= htmlspecialchars($t['ongoing']) ?></option>
                        <option value="terminee" <?= $filter_statut === 'terminee' ? 'selected' : '' ?>><?= htmlspecialchars($t['completed']) ?></option>
                        <option value="en_attente" <?= $filter_statut === 'en_attente' ? 'selected' : '' ?>><?= htmlspecialchars($t['pending']) ?></option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="data-table" id="panneTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable('id')" class="<?= $sort_by === 'id' ? 'sorted' : '' ?>">
                                <?= htmlspecialchars($t['id']) ?>
                                <i class="bi bi-arrow-<?= $sort_by === 'id' && $sort_order === 'ASC' ? 'up' : 'down' ?> sort-icon"></i>
                            </th>
                            <th onclick="sortTable('equipement')" class="<?= $sort_by === 'equipement' ? 'sorted' : '' ?>">
                                <?= htmlspecialchars($t['equipment']) ?>
                                <i class="bi bi-arrow-<?= $sort_by === 'equipement' && $sort_order === 'ASC' ? 'up' : 'down' ?> sort-icon"></i>
                            </th>
                            <th onclick="sortTable('type_panne')" class="<?= $sort_by === 'type_panne' ? 'sorted' : '' ?>">
                                <?= htmlspecialchars($t['breakdown_type']) ?>
                                <i class="bi bi-arrow-<?= $sort_by === 'type_panne' && $sort_order === 'ASC' ? 'up' : 'down' ?> sort-icon"></i>
                            </th>
                            <th><?= htmlspecialchars($t['description']) ?></th>
                            <th onclick="sortTable('date_debut')" class="<?= $sort_by === 'date_debut' ? 'sorted' : '' ?>">
                                <?= htmlspecialchars($t['start_date']) ?>
                                <i class="bi bi-arrow-<?= $sort_by === 'date_debut' && $sort_order === 'ASC' ? 'up' : 'down' ?> sort-icon"></i>
                            </th>
                            <th onclick="sortTable('date_fin')" class="<?= $sort_by === 'date_fin' ? 'sorted' : '' ?>">
                                <?= htmlspecialchars($t['end_date']) ?>
                                <i class="bi bi-arrow-<?= $sort_by === 'date_fin' && $sort_order === 'ASC' ? 'up' : 'down' ?> sort-icon"></i>
                            </th>
                            <th onclick="sortTable('priorite')" class="<?= $sort_by === 'priorite' ? 'sorted' : '' ?>">
                                <?= htmlspecialchars($t['priority']) ?>
                                <i class="bi bi-arrow-<?= $sort_by === 'priorite' && $sort_order === 'ASC' ? 'up' : 'down' ?> sort-icon"></i>
                            </th>
                            <th onclick="sortTable('statut')" class="<?= $sort_by === 'statut' ? 'sorted' : '' ?>">
                                <?= htmlspecialchars($t['status']) ?>
                                <i class="bi bi-arrow-<?= $sort_by === 'statut' && $sort_order === 'ASC' ? 'up' : 'down' ?> sort-icon"></i>
                            </th>
                            <th onclick="sortTable('usine')" class="<?= $sort_by === 'usine' ? 'sorted' : '' ?>">
                                <?= htmlspecialchars($t['factory']) ?>
                                <i class="bi bi-arrow-<?= $sort_by === 'usine' && $sort_order === 'ASC' ? 'up' : 'down' ?> sort-icon"></i>
                            </th>
                            <th><?= htmlspecialchars($t['actions']) ?></th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php if (empty($pannes)): ?>
                        <tr>
                            <td colspan="10" class="text-center" style="padding: 40px;">
                                <i class="bi bi-inbox" style="font-size: 48px; color: var(--text-secondary); opacity: 0.5;"></i>
                                <p style="color: var(--text-secondary); margin-top: 10px;"><?= htmlspecialchars($t['no_breakdown_found']) ?></p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php 
                            $priorite_labels = [
                                'basse' => $t['low'],
                                'moyenne' => $t['medium'],
                                'haute' => $t['high'],
                                'critique' => $t['critical']
                            ];
                            $statut_labels = [
                                'en_cours' => $t['ongoing'],
                                'terminee' => $t['completed'],
                                'en_attente' => $t['pending']
                            ];
                            ?>
                            <?php foreach ($pannes as $index => $panne): ?>
                            <tr class="panne-row" data-index="<?= $index ?>">
                                <td><strong>#<?= htmlspecialchars($panne['id']) ?></strong></td>
                                <td><?= htmlspecialchars($panne['equipement']) ?></td>
                                <td><?= htmlspecialchars($panne['type_panne']) ?></td>
                                <td><?= htmlspecialchars(substr($panne['description'], 0, 50)) ?><?= strlen($panne['description']) > 50 ? '...' : '' ?></td>
                                <td><?= htmlspecialchars($panne['date_debut']) ?></td>
                                <td><?= $panne['date_fin'] ? htmlspecialchars($panne['date_fin']) : '-' ?></td>
                                <td>
                                    <span class="badge badge-<?= htmlspecialchars($panne['priorite']) ?>">
                                        <?= htmlspecialchars($priorite_labels[$panne['priorite']] ?? $panne['priorite']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= htmlspecialchars($panne['statut']) ?>">
                                        <?= htmlspecialchars($statut_labels[$panne['statut']] ?? $panne['statut']) ?>
                                    </span>
                                </td>
                                <td><strong><?= htmlspecialchars($panne['usine']) ?></strong></td>
                                <td>
                                    <button class="btn-action btn-edit" onclick="editPanne(<?= htmlspecialchars(json_encode($panne)) ?>)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn-action btn-delete" onclick="if(confirm('<?= htmlspecialchars($t['confirm_delete']) ?>')) window.location.href='?delete=<?= $panne['id'] ?>'">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($pannes)): ?>
            <div class="pagination-info">
                <div>
                    <span id="displayInfo" style="color: var(--text-secondary);">
                        Affichage de <strong style="color: var(--text-primary);" id="rangeStart">1</strong>-<strong style="color: var(--text-primary);" id="rangeEnd">10</strong> sur <strong style="color: var(--text-primary);"><?= count($pannes) ?></strong>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <button class="btn-load-more" id="prevBtn" onclick="previousPage()" style="display: none;">
                        <i class="bi bi-chevron-left"></i> Précédent
                    </button>
                    <span style="color: var(--text-secondary); font-weight: 600;">
                        Page <span id="currentPage">1</span> sur <span id="totalPages">1</span>
                    </span>
                    <button class="btn-load-more" id="nextBtn" onclick="nextPage()">
                        Suivant <i class="bi bi-chevron-right"></i>
                    </button>
                    <select class="filter-select" id="rowsPerPageSelect" onchange="changeRowsPerPage(this.value)" style="padding: 8px 12px; min-width: 80px;">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalPanne" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"><?= htmlspecialchars($t['add_breakdown_title']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="panneId">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= htmlspecialchars($t['equipment']) ?></label>
                                <select class="form-select" name="id_equipement" id="panneEquipement" required>
                                    <option value=""><?= htmlspecialchars($t['select_equipment']) ?></option>
                                    <?php foreach ($equipements as $eq): ?>
                                        <option value="<?= $eq['id'] ?>"><?= htmlspecialchars($eq['nom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= htmlspecialchars($t['breakdown_type']) ?></label>
                                <input type="text" class="form-control" name="type_panne" id="panneType" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><?= htmlspecialchars($t['description']) ?></label>
                            <textarea class="form-control" name="description" id="panneDescription" rows="3" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= htmlspecialchars($t['start_date']) ?></label>
                                <input type="datetime-local" class="form-control" name="date_debut" id="panneDebut" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= htmlspecialchars($t['end_date']) ?></label>
                                <input type="datetime-local" class="form-control" name="date_fin" id="panneFin">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><?= htmlspecialchars($t['priority']) ?></label>
                                <select class="form-select" name="priorite" id="pannePriorite" required>
                                    <option value="basse"><?= htmlspecialchars($t['low']) ?></option>
                                    <option value="moyenne"><?= htmlspecialchars($t['medium']) ?></option>
                                    <option value="haute"><?= htmlspecialchars($t['high']) ?></option>
                                    <option value="critique"><?= htmlspecialchars($t['critical']) ?></option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><?= htmlspecialchars($t['status']) ?></label>
                                <select class="form-select" name="statut" id="panneStatut" required>
                                    <option value="en_cours"><?= htmlspecialchars($t['ongoing']) ?></option>
                                    <option value="terminee"><?= htmlspecialchars($t['completed']) ?></option>
                                    <option value="en_attente"><?= htmlspecialchars($t['pending']) ?></option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><?= htmlspecialchars($t['factory']) ?></label>
                                <select class="form-select" name="usine" id="panneUsine" required>
                                    <option value="DMA1">DMA1</option>
                                    <option value="DMA2">DMA2</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= htmlspecialchars($t['cancel']) ?></button>
                        <button type="submit" class="btn btn-danger"><?= htmlspecialchars($t['save']) ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

        function resetForm() {
            document.getElementById('panneId').value = '';
            document.getElementById('panneEquipement').value = '';
            document.getElementById('panneType').value = '';
            document.getElementById('panneDescription').value = '';
            document.getElementById('panneDebut').value = '';
            document.getElementById('panneFin').value = '';
            document.getElementById('pannePriorite').value = 'basse';
            document.getElementById('panneStatut').value = 'en_cours';
            document.getElementById('panneUsine').value = 'DMA1';
            document.getElementById('modalTitle').textContent = '<?= htmlspecialchars($t['add_breakdown_title']) ?>';
        }

        function editPanne(panne) {
            document.getElementById('panneId').value = panne.id;
            document.getElementById('panneEquipement').value = panne.id_equipement;
            document.getElementById('panneType').value = panne.type_panne;
            document.getElementById('panneDescription').value = panne.description;
            document.getElementById('panneDebut').value = panne.date_debut.replace(' ', 'T');
            document.getElementById('panneFin').value = panne.date_fin ? panne.date_fin.replace(' ', 'T') : '';
            document.getElementById('pannePriorite').value = panne.priorite;
            document.getElementById('panneStatut').value = panne.statut;
            document.getElementById('panneUsine').value = panne.usine;
            document.getElementById('modalTitle').textContent = '<?= htmlspecialchars($t['edit_breakdown_title']) ?>';
            
            const modal = new bootstrap.Modal(document.getElementById('modalPanne'));
            modal.show();
        }

        function updateFilters(type, value) {
            const url = new URL(window.location.href);
            url.searchParams.set(type, value);
            // Préserver les paramètres de tri
            const currentSort = '<?= $sort_by ?>';
            const currentOrder = '<?= $sort_order ?>';
            url.searchParams.set('sort', currentSort);
            url.searchParams.set('order', currentOrder);
            window.location.href = url.toString();
        }

        // Variables globales pour la pagination
        let currentPage = 1;
        let rowsPerPage = 10;
        let totalRows = <?= count($pannes) ?>;

        // Initialisation au chargement
        document.addEventListener('DOMContentLoaded', function() {
            updatePagination();

            // Auto-dismiss alerts après 5 secondes
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Detect system theme
            if (document.body.getAttribute('data-theme') === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.body.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
            }

            // Marquer l'élément actif dans le menu
            setActiveMenuItem();
        });

        // Fonction pour marquer l'élément actif du menu
        function setActiveMenuItem() {
            const currentPagePath = window.location.pathname.split('/').pop().split('?')[0];
            const menuLinks = document.querySelectorAll('.menu-link');
            
            menuLinks.forEach(link => {
                link.classList.remove('active');
                const linkPage = link.getAttribute('href').split('?')[0];
                if (linkPage === currentPagePath) {
                    link.classList.add('active');
                }
            });
        }

        // Mettre à jour la pagination
        function updatePagination() {
            const rows = document.querySelectorAll('.panne-row');
            const totalPages = Math.ceil(totalRows / rowsPerPage);
            
            // Afficher/Masquer les lignes
            rows.forEach((row, index) => {
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                
                if (index >= start && index < end) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Mettre à jour les infos
            const rangeStart = totalRows > 0 ? (currentPage - 1) * rowsPerPage + 1 : 0;
            const rangeEnd = Math.min(currentPage * rowsPerPage, totalRows);
            
            document.getElementById('rangeStart').textContent = rangeStart;
            document.getElementById('rangeEnd').textContent = rangeEnd;
            document.getElementById('currentPage').textContent = currentPage;
            document.getElementById('totalPages').textContent = totalPages;

            // Boutons navigation
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            if (prevBtn) {
                prevBtn.style.display = currentPage > 1 ? 'inline-flex' : 'none';
            }
            
            if (nextBtn) {
                nextBtn.style.display = currentPage < totalPages ? 'inline-flex' : 'none';
            }
        }

        // Page suivante
        function nextPage() {
            const totalPages = Math.ceil(totalRows / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
                scrollToTop();
            }
        }

        // Page précédente
        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
                scrollToTop();
            }
        }

        // Changer le nombre de lignes par page
        function changeRowsPerPage(value) {
            rowsPerPage = parseInt(value);
            currentPage = 1;
            updatePagination();
        }

        // Scroll vers le haut du tableau
        function scrollToTop() {
            const tableContainer = document.querySelector('.table-container');
            if (tableContainer) {
                tableContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Change Theme
        function changeTheme(theme) {
            fetch('change_theme.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'theme=' + theme
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) location.reload();
            });
        }

        // Fonction de tri
        function sortTable(column) {
            const currentSort = '<?= $sort_by ?>';
            const currentOrder = '<?= $sort_order ?>';
            const filter = '<?= $filter_usine ?>';
            const filterPriorite = '<?= $filter_priorite ?>';
            const filterStatut = '<?= $filter_statut ?>';
            
            let newOrder = 'ASC';
            if (column === currentSort && currentOrder === 'ASC') {
                newOrder = 'DESC';
            }
            
            window.location.href = `?sort=${column}&order=${newOrder}&filter=${filter}&priorite=${filterPriorite}&statut=${filterStatut}`;
        }
    </script>
</body>
</html>