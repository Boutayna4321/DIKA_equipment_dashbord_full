<?php
require __DIR__ . '/auth_check.php';
$selected = $_SESSION['lang'] ?? 'fr';
require __DIR__ . '/../lang.php';
$t = $lang[$selected] ?? $lang['fr'];
$site = $_GET['site'] ?? 'GLOBAL';
$user_name = $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'];
$user_role = $_SESSION['user_role'];
$theme = $_SESSION['theme'] ?? 'system';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($selected) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['dashboard'] ?? 'Dashboard') ?> - DIKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="/DIKA_equipment_dashbord_full/public/images/favicon.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1/dist/chartjs-plugin-zoom.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    
    <style>
         * { margin: 0; padding: 0; box-sizing: border-box; }
        
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
            /* Mode Dark préservé mais plus clair */
            --bg-overlay: rgba(15, 23, 42, 0.9);
            --header-bg-start: #1e293b;
            --header-bg-end: #0f172a;
            --sidebar-bg: rgba(30, 41, 59, 0.95);
            --sidebar-hover: rgba(37, 99, 235, 0.15);
            --sidebar-active: rgba(37, 99, 235, 0.25);
            --card-bg: rgba(51, 65, 85, 0.95);
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-color: rgba(100, 116, 139, 0.4);
            --hover-bg: rgba(37, 99, 235, 0.15);
            --active-bg: rgba(37, 99, 235, 0.25);
            
            /* Couleurs pour les en-têtes de table en mode sombre */
            --table-header-bg: rgba(37, 99, 235, 0.2);
            --table-header-hover: rgba(37, 99, 235, 0.3);
            --table-header-text: #f1f5f9;
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
            top: 0; left: 0; right: 0; bottom: 0;
            background: var(--bg-overlay);
            z-index: 0;
        }
        .dashboard-wrapper { position: relative; z-index: 1; display: flex; min-height: 100vh; }
        
        .top-header {
            position: fixed; top: 0; left: 0; right: 0; height: 70px;
            background: linear-gradient(135deg, var(--header-bg-start) 0%, var(--header-bg-end) 100%);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 30px; z-index: 1000;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        }
        .header-left { display: flex; align-items: center; gap: 20px; }
        .menu-toggle { 
            background: none; border: none; color: var(--text-primary); font-size: 24px; 
            cursor: pointer; padding: 8px 12px; border-radius: 8px; 
            transition: all 0.3s ease; 
        }
        .menu-toggle:hover { background: var(--hover-bg); }
        .brand-header { display: flex; align-items: center; gap: 15px; }
        .logo-header { width: 45px; height: 45px; background: white; border-radius: 8px; padding: 5px; border: 1px solid var(--border-color); }
        .logo-header img { width: 100%; height: 100%; object-fit: contain; }
        .brand-text-header h1 { color: var(--text-primary); font-size: 18px; font-weight: 700; margin: 0; }
        .brand-text-header p { color: var(--text-secondary); font-size: 12px; margin: 0; }
        .header-right { display: flex; align-items: center; gap: 20px; }
        
        .theme-selector, .lang-switch, .user-section { 
            display: flex; gap: 5px; background: var(--hover-bg); 
            padding: 5px; border-radius: 8px; align-items: center; 
            border: 1px solid var(--border-color);
        }
        .theme-btn, .lang-switch a { 
            background: transparent; border: none; 
            color: var(--text-secondary); padding: 6px 12px; 
            cursor: pointer; font-size: 14px; border-radius: 6px; 
            transition: all 0.3s ease; text-decoration: none; 
        }
        .theme-btn.active, .theme-btn:hover, .lang-switch a.active, .lang-switch a:hover { 
            background: var(--primary); color: white; 
        }
        
        .user-info-header { text-align: right; }
        .user-name-header { color: var(--text-primary); font-size: 14px; font-weight: 600; }
        .user-role-header { color: var(--text-secondary); font-size: 11px; text-transform: uppercase; }
        .logout-btn-header { 
            background: var(--danger); color: white; border: none; 
            padding: 8px 20px; border-radius: 8px; font-size: 13px; 
            font-weight: 600; cursor: pointer; transition: all 0.3s ease; 
            text-decoration: none; display: inline-block; 
        }
        .logout-btn-header:hover { 
            background: var(--danger-hover); transform: translateY(-2px); color: white; 
        }
        
        .sidebar {
            position: fixed; top: 70px; left: 0; width: 280px; 
            height: calc(100vh - 70px);
            background: var(--sidebar-bg); 
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--border-color);
            transition: all 0.3s ease; overflow-y: auto; z-index: 999;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.05);
        }
        .sidebar.collapsed { width: 70px; }
        .sidebar-menu { list-style: none; padding: 20px 0; margin: 0; }
        .menu-item { margin: 5px 0; }
        .menu-link { 
            display: flex; align-items: center; gap: 15px; 
            padding: 15px 25px; color: var(--text-secondary); 
            text-decoration: none; transition: all 0.3s ease; 
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
            font-size: 20px; width: 24px; text-align: center; 
            transition: transform 0.2s ease;
        }
        .menu-link:hover .menu-icon { transform: scale(1.1); }
        .menu-text { 
            font-size: 14px; font-weight: 500; white-space: nowrap; 
            transition: opacity 0.3s ease; 
        }
        .sidebar.collapsed .menu-text { opacity: 0; width: 0; overflow: hidden; }
        
        .sidebar-divider {
            height: 1px; 
            background: var(--border-color); 
            margin: 15px 25px;
            opacity: 0.6;
        }
        
        .main-content { 
            margin-left: 280px; margin-top: 70px; padding: 30px; 
            width: calc(100% - 280px); transition: all 0.3s ease; 
            background: transparent;
        }
        .sidebar.collapsed ~ .main-content { margin-left: 70px; width: calc(100% - 70px); }
        
        .collapsible-section { 
            background: var(--card-bg); border-radius: 12px; 
            margin-bottom: 20px; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); 
            overflow: hidden; 
            border: 1px solid var(--border-color);
        }
        .section-header { 
            display: flex; justify-content: space-between; align-items: center; 
            padding: 20px 25px; cursor: pointer; 
            background: var(--card-bg); 
            border-bottom: 1px solid var(--border-color); 
            transition: all 0.3s ease; 
        }
        .section-header:hover { background: var(--hover-bg); }
        .section-title { 
            color: var(--text-primary); font-size: 18px; font-weight: 700; 
            display: flex; align-items: center; gap: 10px; 
        }
        .section-toggle { 
            color: var(--text-primary); font-size: 20px; 
            transition: transform 0.3s ease; 
        }
        .section-toggle.collapsed { transform: rotate(-90deg); }
        .section-content { padding: 25px; transition: all 0.3s ease; }
        .section-content.hidden { display: none; }
        
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            gap: 20px; margin-bottom: 20px; 
        }
        .stat-card { 
            background: var(--card-bg); padding: 20px; border-radius: 12px; 
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); 
            transition: all 0.3s ease; 
            border-left: 4px solid var(--primary); 
            border: 1px solid var(--border-color);
        }
        .stat-card:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12); 
        }
        .stat-header { 
            display: flex; justify-content: space-between; 
            align-items: center; margin-bottom: 10px; 
        }
        .stat-title { 
            color: var(--text-secondary); font-size: 12px; 
            font-weight: 600; text-transform: uppercase; 
        }
        .stat-icon { font-size: 24px; opacity: 0.5; }
        .stat-value { 
            color: var(--text-primary); font-size: 32px; 
            font-weight: 700; margin-bottom: 5px; 
        }
        .stat-subtitle { color: var(--text-secondary); font-size: 11px; }
        
        /* Couleurs spécifiques des stat-cards */
        .stat-card.primary { border-left-color: var(--primary); }
        .stat-card.danger { border-left-color: var(--danger); }
        .stat-card.warning { border-left-color: var(--warning); }
        .stat-card.success { border-left-color: var(--success); }
        .stat-card.info { border-left-color: var(--info); }
        
        .comparison-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; }
        .comparison-factory-card { 
            background: var(--card-bg); 
            border: 1px solid var(--border-color); border-radius: 16px; 
            padding: 25px; position: relative; overflow: hidden; 
            transition: all 0.3s ease; 
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }
        .comparison-factory-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12); 
        }
        .factory-header { 
            display: flex; justify-content: space-between; 
            align-items: center; margin-bottom: 25px; 
            position: relative; z-index: 1; 
        }
        .factory-name { 
            font-size: 24px; font-weight: 700; 
            color: var(--text-primary); 
            display: flex; align-items: center; gap: 10px; 
        }
        .factory-badge { 
            background: linear-gradient(135deg, var(--success) 0%, #047857 100%); 
            color: white; padding: 6px 15px; border-radius: 20px; 
            font-size: 12px; font-weight: 600; text-transform: uppercase; 
            display: flex; align-items: center; gap: 5px; 
            box-shadow: 0 2px 12px rgba(5, 150, 105, 0.2); 
        }
        .factory-metrics { 
            display: grid; grid-template-columns: repeat(2, 1fr); 
            gap: 15px; position: relative; z-index: 1; 
        }
        .factory-metric { 
            background: var(--card-bg); padding: 15px; 
            border-radius: 10px; border-left: 3px solid var(--primary); 
            border: 1px solid var(--border-color);
        }
        .metric-label { 
            color: var(--text-secondary); font-size: 11px; 
            font-weight: 600; text-transform: uppercase; margin-bottom: 8px; 
        }
        .metric-value { 
            color: var(--text-primary); font-size: 24px; font-weight: 700; 
        }
        
        .charts-row { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); 
            gap: 20px; 
        }
        .chart-card { 
            background: var(--card-bg); padding: 20px; 
            border-radius: 12px; 
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); 
            border: 1px solid var(--border-color);
        }
        .chart-title { 
            color: var(--text-primary); font-size: 14px; 
            font-weight: 600; text-transform: uppercase; 
            margin-bottom: 15px; display: flex; 
            align-items: center; gap: 8px; 
        }
        .chart-container { position: relative; height: 250px; }
        
        .filters-bar { 
            display: flex; align-items: flex-end; gap: 10px; 
            margin-bottom: 20px; padding: 15px; 
            background: var(--hover-bg); 
            border-radius: 8px; flex-wrap: wrap; 
            border: 1px solid var(--border-color);
        }
        .filter-group { display: flex; flex-direction: column; gap: 5px; }
        .filter-label { 
            color: var(--text-secondary); font-size: 11px; 
            font-weight: 600; text-transform: uppercase; 
        }
        .filter-input, .filter-select { 
            padding: 8px 12px; border: 1px solid var(--border-color); 
            border-radius: 6px; background: var(--card-bg); 
            color: var(--text-primary); font-size: 13px; 
            transition: all 0.3s ease; 
        }
        .filter-input:focus, .filter-select:focus { 
            outline: none; border-color: var(--primary); 
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); 
        }
        
        .btn-action { 
            padding: 8px 16px; border: none; border-radius: 6px; 
            font-size: 13px; font-weight: 600; cursor: pointer; 
            transition: all 0.3s ease; display: inline-flex; 
            align-items: center; gap: 6px; 
        }
        .btn-action.primary { background: var(--primary); color: white; }
        .btn-action.primary:hover { 
            background: var(--primary-dark); transform: translateY(-2px); 
        }
        .btn-action.secondary { 
            background: var(--card-bg); 
            color: var(--text-primary); 
            border: 1px solid var(--border-color); 
        }
        .btn-action.secondary:hover { background: var(--hover-bg); }
        
        /* Styles améliorés pour les tables */
        .data-table { 
            width: 100%; border-collapse: separate; 
            border-spacing: 0; border-radius: 8px; 
            overflow: hidden;
        }
        .data-table thead { 
            background: var(--table-header-bg);
            position: sticky; top: 0;
            transition: background-color 0.3s ease;
        }
        .data-table thead:hover {
            background: var(--table-header-hover);
        }
        .data-table th { 
            padding: 14px 12px; text-align: left; 
            color: var(--table-header-text); 
            font-size: 12px; font-weight: 700; text-transform: uppercase;
            border-bottom: 2px solid var(--primary);
            transition: all 0.3s ease;
            position: relative;
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
        .data-table td { 
            padding: 12px; border-bottom: 1px solid var(--border-color); 
            color: var(--text-secondary); font-size: 13px; 
            transition: background-color 0.2s ease;
        }
        .data-table tbody tr:hover { 
            background: var(--hover-bg); 
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .data-table tbody tr {
            transition: all 0.2s ease;
        }
        
        .pagination-container { 
            display: flex; justify-content: space-between; 
            align-items: center; margin-top: 20px; padding: 15px; 
            background: var(--hover-bg); border-radius: 8px; 
            border: 1px solid var(--border-color);
        }
        .pagination-info { color: var(--text-secondary); font-size: 13px; }
        .pagination-controls { display: flex; gap: 10px; align-items: center; }
        .page-btn { 
            background: var(--card-bg); border: 1px solid var(--border-color); 
            color: var(--text-primary); padding: 8px 15px; 
            border-radius: 6px; cursor: pointer; font-size: 13px; 
            font-weight: 600; transition: all 0.3s ease; 
            display: inline-flex; align-items: center; gap: 5px; 
        }
        .page-btn:hover:not(:disabled) { 
            background: var(--primary); color: white; 
            border-color: var(--primary); 
        }
        .page-btn:disabled { opacity: 0.4; cursor: not-allowed; }
        .page-number { 
            color: var(--text-primary); font-weight: 600; padding: 0 10px; 
        }
        
        /* Badges avec icônes pour l'accessibilité */
        .status-badge { 
            padding: 4px 10px; border-radius: 12px; 
            font-size: 11px; font-weight: 600; 
            text-transform: uppercase; display: inline-flex;
            align-items: center; gap: 4px;
        }
        .status-badge.en_cours { background: rgba(245, 158, 11, 0.15); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.3); }
        .status-badge.en_cours::before { font-family: "Font Awesome 6 Free"; font-weight: 900; content: "\f252"; }
        .status-badge.terminee { background: rgba(5, 150, 105, 0.15); color: #047857; border: 1px solid rgba(5, 150, 105, 0.3); }
        .status-badge.terminee::before { font-family: "Font Awesome 6 Free"; font-weight: 900; content: "\f00c"; }
        .status-badge.en_attente { background: rgba(37, 99, 235, 0.15); color: var(--primary); border: 1px solid rgba(37, 99, 235, 0.3); }
        .status-badge.en_attente::before { font-family: "Font Awesome 6 Free"; font-weight: 900; content: "\f04c"; }
        
        .priority-badge { 
            padding: 4px 10px; border-radius: 12px; 
            font-size: 11px; font-weight: 600; 
            text-transform: uppercase; display: inline-flex;
            align-items: center; gap: 4px;
        }
        .priority-badge.critique { background: rgba(220, 38, 38, 0.15); color: #b91c1c; border: 1px solid rgba(220, 38, 38, 0.3); }
        .priority-badge.critique::before { font-family: "Font Awesome 6 Free"; font-weight: 900; content: "\f071"; }
        .priority-badge.haute { background: rgba(249, 115, 22, 0.15); color: #c2410c; border: 1px solid rgba(249, 115, 22, 0.3); }
        .priority-badge.haute::before { font-family: "Font Awesome 6 Free"; font-weight: 900; content: "\f062"; }
        .priority-badge.moyenne { background: rgba(245, 158, 11, 0.15); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.3); }
        .priority-badge.moyenne::before { font-family: "Font Awesome 6 Free"; font-weight: 900; content: "\f061"; }
        .priority-badge.basse { background: rgba(5, 150, 105, 0.15); color: #047857; border: 1px solid rgba(5, 150, 105, 0.3); }
        .priority-badge.basse::before {  font-family: "Font Awesome 6 Free"; font-weight: 900; content: "\f063"; }
        
        .loading { text-align: center; padding: 40px; color: var(--text-secondary); }
        .loading-spinner { 
            display: inline-block; width: 40px; height: 40px; 
            border: 4px solid rgba(37, 99, 235, 0.2); 
            border-top-color: var(--primary); 
            border-radius: 50%; animation: spin 1s linear infinite; 
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; }
            .stats-grid, .charts-row, .comparison-grid { grid-template-columns: 1fr; }
            .factory-metrics { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body data-theme="<?= $theme ?>">
    <div class="dashboard-wrapper">
        <header class="top-header">
            <div class="header-left">
                <button class="menu-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
                <div class="brand-header">
                    <div class="logo-header"><img src="images/logo.jpeg" alt="DIKA Logo"></div>
                    <div class="brand-text-header">
                        <h1><?= htmlspecialchars($t['brand'] ?? 'DIKA') ?></h1>
                        <p><?= htmlspecialchars($t['dept'] ?? 'Maintenance') ?></p>
                    </div>
                </div>
            </div>
            <div class="header-right">
                <div class="theme-selector">
                    <button class="theme-btn <?= $theme === 'light' ? 'active' : '' ?>" onclick="changeTheme('light')"><i class="bi bi-sun-fill"></i></button>
                    <button class="theme-btn <?= $theme === 'dark' ? 'active' : '' ?>" onclick="changeTheme('dark')"><i class="bi bi-moon-fill"></i></button>
                    <button class="theme-btn <?= $theme === 'system' ? 'active' : '' ?>" onclick="changeTheme('system')"><i class="bi bi-circle-half"></i></button>
                </div>
                <div class="lang-switch">
                    <a href="change_lang.php?lang=fr&redirect=dashboard&site=<?= $site ?>" class="<?= $selected === 'fr' ? 'active' : '' ?>">FR</a>
                    <a href="change_lang.php?lang=en&redirect=dashboard&site=<?= $site ?>" class="<?= $selected === 'en' ? 'active' : '' ?>">EN</a>
                    <a href="change_lang.php?lang=cn&redirect=dashboard&site=<?= $site ?>" class="<?= $selected === 'cn' ? 'active' : '' ?>">中文</a>
                </div>
                <div class="user-section">
                    <div class="user-info-header">
                        <span class="user-name-header"><?= htmlspecialchars($user_name) ?></span>
                        <span class="user-role-header"><?= htmlspecialchars($user_role) ?></span>
                    </div>
                    <a href="logout.php" class="logout-btn-header"><?= htmlspecialchars($t['logout'] ?? 'Déconnexion') ?></a>
                </div>

                
            </div>
        </header>

        <aside class="sidebar" id="sidebar">
            <ul class="sidebar-menu">
                <li class="menu-item"><a href="?site=GLOBAL" class="menu-link <?= $site === 'GLOBAL' ? 'active' : '' ?>"><span class="menu-icon"><i class="fa-solid fa-chart-column"></i>
                </span><span class="menu-text">Global</span></a></li>
                <li class="menu-item"><a href="?site=DMA1" class="menu-link <?= $site === 'DMA1' ? 'active' : '' ?>"><span class="menu-icon"><i class="fa-solid fa-industry"></i></span><span class="menu-text">DMA1</span></a></li>
                <li class="menu-item"><a href="?site=DMA2" class="menu-link <?= $site === 'DMA2' ? 'active' : '' ?>"><span class="menu-icon"><i class="fa-solid fa-industry"></i></span><span class="menu-text">DMA2</span></a></li>
                <div style="height: 1px; background: rgba(255, 255, 255, 0.1); margin: 15px 0;"></div>
                <li class="menu-item"><a href="equipements_admin.php" class="menu-link"><span class="menu-icon"><i class="fa-solid fa-gear"></i>
                </span><span class="menu-text"><?= htmlspecialchars($t['manage_equipment'] ?? 'Équipements') ?></span></a></li>
                <li class="menu-item"><a href="pannes_admin.php" class="menu-link"><span class="menu-icon"><i class="fa-solid fa-wrench"></i>
                </span><span class="menu-text"><?= htmlspecialchars($t['manage_breakdowns'] ?? 'Pannes') ?></span></a></li>
                <li class="menu-item"><a href="interventions_admin.php" class="menu-link"><span class="menu-icon"><i class="fa-solid fa-screwdriver-wrench"></i>
                </span><span class="menu-text"><?= htmlspecialchars($t['manage_interventions'] ?? 'Interventions') ?></span></a></li>
            </ul>
        </aside>

        <main class="main-content">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-header">
                        <div class="stat-title"><?= htmlspecialchars($t['total_equipment'] ?? 'Total Équipements') ?></div>
                        <div class="stat-icon"><i class="fa-solid fa-gear"></i>
                        </div>
                    </div>
                    <div class="stat-value" id="equipements">0</div>
                    <div class="stat-subtitle"><?= htmlspecialchars($t['machines_in_fleet'] ?? 'Machines en parc') ?></div>
                </div>
                <div class="stat-card danger">
                    <div class="stat-header">
                        <div class="stat-title"><?= htmlspecialchars($t['ongoing_breakdowns'] ?? 'Pannes en cours') ?></div>
                        <div class="stat-icon"><i class="fa-solid fa-wrench"></i>
                        </div>
                    </div>
                    <div class="stat-value" id="pannes">0</div>
                    <div class="stat-subtitle"><?= htmlspecialchars($t['require_intervention'] ?? 'Nécessitent intervention') ?></div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-header">
                        <div class="stat-title"><?= htmlspecialchars($t['interventions'] ?? 'Interventions') ?></div>
                        <div class="stat-icon"><i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>
                    </div>
                    <div class="stat-value" id="interventions">0</div>
                    <div class="stat-subtitle"><?= htmlspecialchars($t['active'] ?? 'Actives') ?></div>
                </div>
                <div class="stat-card success">
                    <div class="stat-header">
                        <div class="stat-title"><?= htmlspecialchars($t['availability'] ?? 'Disponibilité') ?></div>
                        <div class="stat-icon"><i class="fa-solid fa-check"></i>                        </div>
                    </div>
                    <div class="stat-value" id="disponibilite">0%</div>
                    <div class="stat-subtitle"><?= htmlspecialchars($t['operational_rate'] ?? 'Taux opérationnel') ?></div>
                </div>
                <div class="stat-card info">
                    <div class="stat-header">
                        <div class="stat-title">MTTR</div>
                        <div class="stat-icon"><i class="fa-solid fa-stopwatch"></i>
                        </div>
                    </div>
                    <div class="stat-value" id="mttr">0h</div>
                    <div class="stat-subtitle"><?= htmlspecialchars($t['mean_repair_time'] ?? 'Temps moyen réparation') ?></div>
                </div>
                <div class="stat-card info">
                    <div class="stat-header">
                        <div class="stat-title">MTBF</div>
                        <div class="stat-icon"><i class="fa-solid fa-chart-line"></i></div>
                    </div>
                    <div class="stat-value" id="mtbf">0j</div>
                    <div class="stat-subtitle"><?= htmlspecialchars($t['time_between_failures'] ?? 'Temps entre pannes') ?></div>
                </div>
            </div>

            <?php if ($site === 'GLOBAL'): ?>
            <div class="collapsible-section" id="comparisonSection">
                <div class="section-header" onclick="toggleSection('comparisonContent')">
                    <div class="section-title"><i class="bi bi-bar-chart-line"></i><span><?= htmlspecialchars($t['comparison_dma'] ?? 'Comparaison DMA1 vs DMA2') ?></span></div>
                    <i class="bi bi-chevron-down section-toggle" id="comparisonToggle"></i>
                </div>
                <div class="section-content" id="comparisonContent">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; margin-bottom: 30px;">
                        <div class="chart-card">
                            <div class="chart-title" style="font-size: 20px; text-align: center; margin-bottom: 10px;">
                                <span><i class="fa-solid fa-industry"></i> DMA1</span>
                                <div class="factory-badge" id="dma1Badge" style="display: none; margin: 10px auto 0; width: fit-content;">
                                    <i class="bi bi-trophy-fill"></i><span><?= htmlspecialchars($t['best'] ?? 'Meilleur') ?></span>
                                </div>
                            </div>
                            <div id="chartDMA1Pie3D" style="height: 400px;"></div>
                        </div>
                        <div class="chart-card">
                            <div class="chart-title" style="font-size: 20px; text-align: center; margin-bottom: 10px;">
                                <span><i class="fa-solid fa-industry"></i> DMA2</span>
                                <div class="factory-badge" id="dma2Badge" style="display: none; margin: 10px auto 0; width: fit-content;">
                                    <i class="bi bi-trophy-fill"></i><span><?= htmlspecialchars($t['best'] ?? 'Meilleur') ?></span>
                                </div>
                            </div>
                            <div id="chartDMA2Pie3D" style="height: 400px;"></div>
                        </div>
                    </div>
                    
                    <div class="comparison-grid">
                        <div class="comparison-factory-card">
                            <div class="factory-header"><div class="factory-name"><span><i class="fa-solid fa-chart-column"></i>
                            </span><span><?= htmlspecialchars($t['details'] ?? 'Détails') ?> DMA1</span></div></div>
                            <div class="factory-metrics">
                                <div class="factory-metric"><div class="metric-label"><?= htmlspecialchars($t['total_equipment'] ?? 'Équipements') ?></div><div class="metric-value" id="dma1Equip">0</div></div>
                                <div class="factory-metric"><div class="metric-label"><?= htmlspecialchars($t['ongoing_breakdowns'] ?? 'Pannes') ?></div><div class="metric-value" id="dma1Pannes">0</div></div>
                                <div class="factory-metric"><div class="metric-label"><?= htmlspecialchars($t['interventions'] ?? 'Interventions') ?></div><div class="metric-value" id="dma1Interv">0</div></div>
                                <div class="factory-metric"><div class="metric-label"><?= htmlspecialchars($t['availability'] ?? 'Disponibilité') ?></div><div class="metric-value" id="dma1Dispo">0%</div></div>
                                <div class="factory-metric"><div class="metric-label">MTTR</div><div class="metric-value" id="dma1Mttr">0h</div></div>
                                <div class="factory-metric"><div class="metric-label">MTBF</div><div class="metric-value" id="dma1Mtbf">0j</div></div>
                            </div>
                        </div>
                        <div class="comparison-factory-card">
                            <div class="factory-header"><div class="factory-name"><span><i class="fa-solid fa-chart-column"></i>
                            </span><span><?= htmlspecialchars($t['details'] ?? 'Détails') ?> DMA2</span></div></div>
                            <div class="factory-metrics">
                                <div class="factory-metric"><div class="metric-label"><?= htmlspecialchars($t['total_equipment'] ?? 'Équipements') ?></div><div class="metric-value" id="dma2Equip">0</div></div>
                                <div class="factory-metric"><div class="metric-label"><?= htmlspecialchars($t['ongoing_breakdowns'] ?? 'Pannes') ?></div><div class="metric-value" id="dma2Pannes">0</div></div>
                                <div class="factory-metric"><div class="metric-label"><?= htmlspecialchars($t['interventions'] ?? 'Interventions') ?></div><div class="metric-value" id="dma2Interv">0</div></div>
                                <div class="factory-metric"><div class="metric-label"><?= htmlspecialchars($t['availability'] ?? 'Disponibilité') ?></div><div class="metric-value" id="dma2Dispo">0%</div></div>
                                <div class="factory-metric"><div class="metric-label">MTTR</div><div class="metric-value" id="dma2Mttr">0h</div></div>
                                <div class="factory-metric"><div class="metric-label">MTBF</div><div class="metric-value" id="dma2Mtbf">0j</div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Filtres de Période -->
            <div class="collapsible-section">
                <div class="section-header" onclick="toggleSection('filtersContent')">
                    <div class="section-title"><i class="bi bi-funnel"></i><span><?= htmlspecialchars($t['period_filters'] ?? 'Filtres de Période') ?></span></div>
                    <i class="bi bi-chevron-down section-toggle" id="filtersToggle"></i>
                </div>
                <div class="section-content" id="filtersContent">
                    <div class="filters-bar">
                        <div class="filter-group">
                            <label class="filter-label"><?= htmlspecialchars($t['period'] ?? 'Période') ?></label>
                            <select class="filter-select" id="chartPeriodFilter">
                                <option value="7"><?= htmlspecialchars($t['last_7_days'] ?? '7 derniers jours') ?></option>
                                <option value="14"><?= htmlspecialchars($t['last_14_days'] ?? '14 derniers jours') ?></option>
                                <option value="30" selected><?= htmlspecialchars($t['last_30_days'] ?? '30 derniers jours') ?></option>
                                <option value="90"><?= htmlspecialchars($t['last_90_days'] ?? '90 derniers jours') ?></option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label"><?= htmlspecialchars($t['start_date'] ?? 'Date de début') ?></label>
                            <input type="date" class="filter-input" id="chartStartDate">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label"><?= htmlspecialchars($t['end_date'] ?? 'Date de fin') ?></label>
                            <input type="date" class="filter-input" id="chartEndDate">
                        </div>
                        <div class="filter-group" style="flex-direction: row; gap: 10px;">
                            <button class="btn-action primary" onclick="applyChartFilters()"><i class="bi bi-check-circle"></i> <?= htmlspecialchars($t['apply'] ?? 'Appliquer') ?></button>
                            <button class="btn-action secondary" onclick="resetChartFilters()"><i class="bi bi-arrow-counterclockwise"></i> <?= htmlspecialchars($t['reset'] ?? 'Réinitialiser') ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="collapsible-section">
                <div class="section-header" onclick="toggleSection('chartsContent')">
                    <div class="section-title"><i class="bi bi-graph-up"></i><span><?= htmlspecialchars($t['evolution_charts'] ?? "Graphiques d'Évolution") ?></span></div>
                    <i class="bi bi-chevron-down section-toggle" id="chartsToggle"></i>
                </div>
                <div class="section-content" id="chartsContent">
                    <div class="charts-row">
                        <div class="chart-card">
                            <div class="chart-title"><i class="bi bi-graph-up"></i> <?= htmlspecialchars($t['evolution'] ?? 'Évolution') ?></div>
                            <div class="chart-container"><canvas id="chartEvolution"></canvas></div>
                        </div>
                        <div class="chart-card">
                            <div class="chart-title"><i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($t['top_10_equipment'] ?? 'Top 10 Équipements') ?></div>
                            <div class="chart-container"><canvas id="chartTopEquip"></canvas></div>
                        </div>
                        <?php if ($site === 'GLOBAL'): ?>
                        <div class="chart-card">
                            <div class="chart-title"><i class="bi bi-bar-chart"></i> <?= htmlspecialchars($t['comparison_7_days'] ?? 'Comparaison DMA1 vs DMA2 (7 jours)') ?></div>
                            <div class="chart-container"><canvas id="chartComparison"></canvas></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="collapsible-section">
                <div class="section-header" onclick="toggleSection('tableContent')">
                    <div class="section-title"><i class="bi bi-table"></i><span><?= htmlspecialchars($t['detailed_data'] ?? 'Données Détaillées') ?></span></div>
                    <i class="bi bi-chevron-down section-toggle" id="tableToggle"></i>
                </div>
                <div class="section-content" id="tableContent">
                    <div class="filters-bar">
                        <div class="filter-group">
                            <label class="filter-label"><?= htmlspecialchars($t['search'] ?? 'Recherche') ?></label>
                            <input type="text" class="filter-input" id="searchInput" placeholder="<?= htmlspecialchars($t['search'] ?? 'Rechercher') ?>..." onkeyup="filterTable()">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label"><?= htmlspecialchars($t['period'] ?? 'Période') ?></label>
                            <select class="filter-select" id="periodFilter" onchange="filterTable()">
                                <option value="all"><?= htmlspecialchars($t['all'] ?? 'Toutes') ?></option>
                                <option value="today"><?= htmlspecialchars($t['today'] ?? "Aujourd'hui") ?></option>
                                <option value="week" selected><?= htmlspecialchars($t['this_week'] ?? 'Cette semaine') ?></option>
                                <option value="month"><?= htmlspecialchars($t['this_month'] ?? 'Ce mois') ?></option>
                                <option value="year"><?= htmlspecialchars($t['this_year'] ?? 'Cette année') ?></option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label"><?= htmlspecialchars($t['site'] ?? 'Site') ?></label>
                            <select class="filter-select" id="siteFilter" onchange="filterTable()">
                                <option value="all"><?= htmlspecialchars($t['all'] ?? 'Tous') ?></option>
                                <option value="DMA1">DMA1</option>
                                <option value="DMA2">DMA2</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label"><?= htmlspecialchars($t['status'] ?? 'Statut') ?></label>
                            <select class="filter-select" id="statusFilter" onchange="filterTable()">
                                <option value="all"><?= htmlspecialchars($t['all'] ?? 'Tous') ?></option>
                                <option value="en_cours"><?= htmlspecialchars($t['in_progress'] ?? 'En cours') ?></option>
                                <option value="terminee"><?= htmlspecialchars($t['completed'] ?? 'Terminé') ?></option>
                                <option value="en_attente"><?= htmlspecialchars($t['pending'] ?? 'En attente') ?></option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label"><?= htmlspecialchars($t['priority'] ?? 'Priorité') ?></label>
                            <select class="filter-select" id="priorityFilter" onchange="filterTable()">
                                <option value="all"><?= htmlspecialchars($t['all'] ?? 'Toutes') ?></option>
                                <option value="critique"><?= htmlspecialchars($t['critical'] ?? 'Critique') ?></option>
                                <option value="haute"><?= htmlspecialchars($t['high'] ?? 'Haute') ?></option>
                                <option value="moyenne"><?= htmlspecialchars($t['medium'] ?? 'Moyenne') ?></option>
                                <option value="basse"><?= htmlspecialchars($t['low'] ?? 'Basse') ?></option>
                            </select>
                        </div>
                        <div class="filter-group" style="flex-direction: row; gap: 10px; margin-left: auto;">
                            <button class="btn-action secondary" onclick="refreshTable()"><i class="bi bi-arrow-clockwise"></i> <?= htmlspecialchars($t['refresh'] ?? 'Actualiser') ?></button>
                            <button class="btn-action primary" onclick="exportToExcel()"><i class="bi bi-file-earmark-excel"></i> <?= htmlspecialchars($t['export'] ?? 'Exporter') ?></button>
                        </div>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="data-table" id="dataTable">
                            <thead id="tableHead"></thead>
                            <tbody id="tableBody">
                                <tr><td colspan="10" class="loading"><div class="loading-spinner"></div><p><?= htmlspecialchars($t['loading'] ?? 'Chargement...') ?></p></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container">
                        <div class="pagination-info" id="paginationInfo"><?= htmlspecialchars($t['display_of'] ?? 'Affichage de') ?> 0-0 <?= htmlspecialchars($t['of'] ?? 'sur') ?> 0 <?= htmlspecialchars($t['results'] ?? 'résultats') ?></div>
                        <div class="pagination-controls">
                            <button class="page-btn" id="firstPageBtn" onclick="goToPage(1)" disabled><i class="bi bi-chevron-double-left"></i> <?= htmlspecialchars($t['first'] ?? 'Premier') ?></button>
                            <button class="page-btn" id="prevPageBtn" onclick="previousPage()" disabled><i class="bi bi-chevron-left"></i> <?= htmlspecialchars($t['previous'] ?? 'Précédent') ?></button>
                            <span class="page-number"><?= htmlspecialchars($t['page'] ?? 'Page') ?> <strong id="currentPageSpan">1</strong> <?= htmlspecialchars($t['of'] ?? 'sur') ?> <strong id="totalPagesSpan">1</strong></span>
                            <button class="page-btn" id="nextPageBtn" onclick="nextPage()"><?= htmlspecialchars($t['next'] ?? 'Suivant') ?> <i class="bi bi-chevron-right"></i></button>
                            <button class="page-btn" id="lastPageBtn" onclick="goToPage('last')"><?= htmlspecialchars($t['last'] ?? 'Dernier') ?> <i class="bi bi-chevron-double-right"></i></button>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label"><?= htmlspecialchars($t['per_page'] ?? 'Par page') ?></label>
                            <select class="filter-select" id="perPageFilter" onchange="changePerPage()">
                                <option value="10">10</option>
                                <option value="25" selected>25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let SITE = '<?= $site ?>';
        let allData = [], filteredData = [], currentPage = 1, perPage = 25, charts = {};
        let chartFilters = { period: 30, startDate: null, endDate: null };
        
        const T = {
            noData: '<?= addslashes($t['no_data'] ?? 'Aucune donnée') ?>',
            exportSuccess: '<?= addslashes($t['export_success'] ?? 'Export réussi !') ?>',
            displayOf: '<?= addslashes($t['display_of'] ?? 'Affichage de') ?>',
            of: '<?= addslashes($t['of'] ?? 'sur') ?>',
            results: '<?= addslashes($t['results'] ?? 'résultats') ?>'
        };

        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('collapsed'); }
        function toggleSection(id) {
            document.getElementById(id).classList.toggle('hidden');
            document.getElementById(id.replace('Content', 'Toggle')).classList.toggle('collapsed');
        }

        function changeTheme(theme) {
            fetch('change_theme.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: 'theme=' + theme })
            .then(r => r.json()).then(d => { if (d.success) location.reload(); });
        }

        async function loadCards() {
            try {
                const r = await fetch('../api/summary_cards.php?site=' + SITE);
                const d = await r.json();
                document.getElementById('equipements').textContent = d.total_equipements || 0;
                document.getElementById('pannes').textContent = d.pannes_en_cours || 0;
                document.getElementById('interventions').textContent = d.interventions_en_cours || 0;
                document.getElementById('disponibilite').textContent = (d.disponibilite || 0) + '%';
                document.getElementById('mttr').textContent = (d.mttr || 0) + 'h';
                document.getElementById('mtbf').textContent = (d.mtbf || 0) + 'j';
            } catch (e) { console.error('Error loading cards:', e); }
        }

        async function loadComparison() {
            if (SITE !== 'GLOBAL') return;
            try {
                const r1 = await fetch('../api/summary_cards.php?site=DMA1');
                const d1 = await r1.json();
                const r2 = await fetch('../api/summary_cards.php?site=DMA2');
                const d2 = await r2.json();

                document.getElementById('dma1Equip').textContent = d1.total_equipements || 0;
                document.getElementById('dma1Pannes').textContent = d1.pannes_en_cours || 0;
                document.getElementById('dma1Interv').textContent = d1.interventions_en_cours || 0;
                document.getElementById('dma1Dispo').textContent = (d1.disponibilite || 0) + '%';
                document.getElementById('dma1Mttr').textContent = (d1.mttr || 0) + 'h';
                document.getElementById('dma1Mtbf').textContent = (d1.mtbf || 0) + 'j';

                document.getElementById('dma2Equip').textContent = d2.total_equipements || 0;
                document.getElementById('dma2Pannes').textContent = d2.pannes_en_cours || 0;
                document.getElementById('dma2Interv').textContent = d2.interventions_en_cours || 0;
                document.getElementById('dma2Dispo').textContent = (d2.disponibilite || 0) + '%';
                document.getElementById('dma2Mttr').textContent = (d2.mttr || 0) + 'h';
                document.getElementById('dma2Mtbf').textContent = (d2.mtbf || 0) + 'j';

                const dispo1 = parseFloat(d1.disponibilite) || 0;
                const dispo2 = parseFloat(d2.disponibilite) || 0;
                document.getElementById('dma1Badge').style.display = dispo1 > dispo2 ? 'flex' : 'none';
                document.getElementById('dma2Badge').style.display = dispo2 > dispo1 ? 'flex' : 'none';

                const isDark = document.body.getAttribute('data-theme') === 'dark';
                const textColor = isDark ? '#f1f5f9' : '#1e293b';

                const pie3DOptions = (container, data) => ({
                    chart: { type: 'pie', renderTo: container, backgroundColor: 'transparent', options3d: { enabled: true, alpha: 55, beta: 0 } },
                    title: { text: null },
                    plotOptions: { pie: { innerSize: 50, depth: 45, allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.percentage:.1f}%', style: { color: textColor, fontSize: '11px' }, connectorColor: textColor }, showInLegend: true } },
                    legend: { itemStyle: { color: textColor } },
                    series: [{ name: 'Valeur', colorByPoint: true, data: data }],
                    credits: { enabled: false }
                });

                const dataDMA1 = [
                    { name: '<?= addslashes($t['total_equipment'] ?? 'Équipements') ?>', y: parseInt(d1.total_equipements) || 1, color: '#2563eb' },
                    { name: '<?= addslashes($t['ongoing_breakdowns'] ?? 'Pannes') ?>', y: parseInt(d1.pannes_en_cours) || 1, color: '#dc2626', sliced: true },
                    { name: '<?= addslashes($t['interventions'] ?? 'Interventions') ?>', y: parseInt(d1.interventions_en_cours) || 1, color: '#f59e0b' },
                    { name: '<?= addslashes($t['availability'] ?? 'Disponibilité') ?>', y: parseFloat(d1.disponibilite) || 1, color: '#059669' },
                    { name: 'MTTR (h)', y: parseFloat(d1.mttr) || 1, color: '#0284c7' },
                    { name: 'MTBF (j)', y: parseFloat(d1.mtbf) || 1, color: '#7c3aed' }
                ];

                const dataDMA2 = [
                    { name: '<?= addslashes($t['total_equipment'] ?? 'Équipements') ?>', y: parseInt(d2.total_equipements) || 1, color: '#2563eb' },
                    { name: '<?= addslashes($t['ongoing_breakdowns'] ?? 'Pannes') ?>', y: parseInt(d2.pannes_en_cours) || 1, color: '#dc2626', sliced: true },
                    { name: '<?= addslashes($t['interventions'] ?? 'Interventions') ?>', y: parseInt(d2.interventions_en_cours) || 1, color: '#f59e0b' },
                    { name: '<?= addslashes($t['availability'] ?? 'Disponibilité') ?>', y: parseFloat(d2.disponibilite) || 1, color: '#059669' },
                    { name: 'MTTR (h)', y: parseFloat(d2.mttr) || 1, color: '#0284c7' },
                    { name: 'MTBF (j)', y: parseFloat(d2.mtbf) || 1, color: '#7c3aed' }
                ];

                Highcharts.chart(pie3DOptions('chartDMA1Pie3D', dataDMA1));
                Highcharts.chart(pie3DOptions('chartDMA2Pie3D', dataDMA2));
            } catch (e) { console.error('Error loading comparison:', e); }
        }

        function applyChartFilters() {
            chartFilters = { period: parseInt(document.getElementById('chartPeriodFilter').value), startDate: document.getElementById('chartStartDate').value || null, endDate: document.getElementById('chartEndDate').value || null };
            loadCharts();
        }

        function resetChartFilters() {
            document.getElementById('chartPeriodFilter').value = '30';
            document.getElementById('chartStartDate').value = '';
            document.getElementById('chartEndDate').value = '';
            chartFilters = { period: 30, startDate: null, endDate: null };
            loadCharts();
        }

        async function loadCharts() {
            const isDark = document.body.getAttribute('data-theme') === 'dark';
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)';

            let url = `../api/daily.php?site=${SITE}&days=${chartFilters.period}`;
            if (chartFilters.startDate && chartFilters.endDate) url += `&start=${chartFilters.startDate}&end=${chartFilters.endDate}`;

            try {
                const r = await fetch(url); const d = await r.json();
                const ctx = document.getElementById('chartEvolution');
                if (charts.evolution) charts.evolution.destroy();
                charts.evolution = new Chart(ctx, { type: 'line', data: { labels: d.labels, datasets: [{ label: '<?= addslashes($t['interventions'] ?? 'Interventions') ?>', data: d.data, backgroundColor: 'rgba(37,99,235,0.1)', borderColor: 'rgba(37,99,235,1)', borderWidth: 2, fill: true, tension: 0.4 }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { color: textColor }, grid: { color: gridColor } }, x: { ticks: { color: textColor }, grid: { color: gridColor } } }, plugins: { legend: { labels: { color: textColor } } } } });
            } catch (e) { console.error('Error evolution:', e); }

            try {
                const r = await fetch('../api/top_equipments.php?site=' + SITE); const d = await r.json();
                const ctx = document.getElementById('chartTopEquip');
                if (charts.topEquip) charts.topEquip.destroy();
                charts.topEquip = new Chart(ctx, { type: 'bar', data: { labels: d.labels, datasets: [{ label: '<?= addslashes($t['ongoing_breakdowns'] ?? 'Pannes') ?>', data: d.data, backgroundColor: 'rgba(220,38,38,0.7)', borderColor: 'rgba(220,38,38,1)', borderWidth: 2 }] }, options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, scales: { x: { beginAtZero: true, ticks: { color: textColor }, grid: { color: gridColor } }, y: { ticks: { color: textColor, font: { size: 10 } }, grid: { color: gridColor } } }, plugins: { legend: { labels: { color: textColor } } } } });
            } catch (e) { console.error('Error top equip:', e); }

            if (SITE === 'GLOBAL') {
                try {
                    const r1 = await fetch('../api/daily.php?site=DMA1&days=7'); const d1 = await r1.json();
                    const r2 = await fetch('../api/daily.php?site=DMA2&days=7'); const d2 = await r2.json();
                    const ctx = document.getElementById('chartComparison');
                    if (charts.comparison) charts.comparison.destroy();
                    charts.comparison = new Chart(ctx, { type: 'bar', data: { labels: d1.labels, datasets: [{ label: 'DMA1', data: d1.data, backgroundColor: 'rgba(37,99,235,0.7)', borderColor: 'rgba(37,99,235,1)', borderWidth: 2 }, { label: 'DMA2', data: d2.data, backgroundColor: 'rgba(8,145,178,0.7)', borderColor: 'rgba(8,145,178,1)', borderWidth: 2 }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { color: textColor }, grid: { color: gridColor } }, x: { ticks: { color: textColor }, grid: { color: gridColor } } }, plugins: { legend: { labels: { color: textColor } } } } });
                } catch (e) { console.error('Error comparison:', e); }
            }
        }

        async function loadTableData() {
            try { const r = await fetch('../api/interventions_list.php?site=' + SITE); allData = await r.json(); filterTable(); }
            catch (e) { console.error('Error table:', e); }
        }

        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const period = document.getElementById('periodFilter').value;
            const site = document.getElementById('siteFilter').value;
            const status = document.getElementById('statusFilter').value;
            const priority = document.getElementById('priorityFilter').value;

            filteredData = allData.filter(item => {
                const searchMatch = !search || Object.values(item).some(v => String(v).toLowerCase().includes(search));
                const siteMatch = site === 'all' || item.site === site;
                const statusMatch = status === 'all' || item.statut === status;
                const priorityMatch = priority === 'all' || item.priorite === priority;
                let periodMatch = true;
                if (period !== 'all' && item.date) {
                    const days = (new Date() - new Date(item.date)) / 86400000;
                    if (period === 'today') periodMatch = days < 1;
                    else if (period === 'week') periodMatch = days < 7;
                    else if (period === 'month') periodMatch = days < 30;
                    else if (period === 'year') periodMatch = days < 365;
                }
                return searchMatch && siteMatch && statusMatch && priorityMatch && periodMatch;
            });
            currentPage = 1; renderTable();
        }

        function renderTable() {
            const thead = document.getElementById('tableHead');
            const tbody = document.getElementById('tableBody');
            const cols = ['ID','Date','Site','Équipement','Type Panne','Priorité','Statut','Durée','Technicien'];
            thead.innerHTML = '<tr>' + cols.map(c => `<th>${c}</th>`).join('') + '</tr>';

            const start = (currentPage - 1) * perPage, end = start + perPage;
            const pageData = filteredData.slice(start, end);
            const totalPages = Math.ceil(filteredData.length / perPage) || 1;

            tbody.innerHTML = pageData.length === 0 ? '<tr><td colspan="9" class="loading">Aucune donnée</td></tr>' :
                pageData.map(i => `<tr><td>${i.id||'-'}</td><td>${i.date||'-'}</td><td>${i.site||'-'}</td><td>${i.equipement||'-'}</td><td>${i.type_panne||'-'}</td><td><span class="priority-badge ${i.priorite||'basse'}">${i.priorite||'-'}</span></td><td><span class="status-badge ${i.statut||'en_attente'}">${i.statut||'-'}</span></td><td>${i.duree||'-'}</td><td>${i.technicien||'-'}</td></tr>`).join('');

            document.getElementById('paginationInfo').textContent = `Affichage de ${start+1}-${Math.min(end, filteredData.length)} sur ${filteredData.length}`;
            document.getElementById('currentPageSpan').textContent = currentPage;
            document.getElementById('totalPagesSpan').textContent = totalPages;
            document.getElementById('firstPageBtn').disabled = document.getElementById('prevPageBtn').disabled = currentPage === 1;
            document.getElementById('nextPageBtn').disabled = document.getElementById('lastPageBtn').disabled = currentPage >= totalPages;
        }

        function nextPage() { if (currentPage < Math.ceil(filteredData.length / perPage)) { currentPage++; renderTable(); } }
        function previousPage() { if (currentPage > 1) { currentPage--; renderTable(); } }
        function goToPage(p) { currentPage = p === 'last' ? Math.ceil(filteredData.length / perPage) : parseInt(p); renderTable(); }
        function changePerPage() { perPage = parseInt(document.getElementById('perPageFilter').value); currentPage = 1; renderTable(); }
        function refreshTable() { loadTableData(); }

        function exportToExcel() {
            const wb = XLSX.utils.book_new();
            const sum = [['DASHBOARD DIKA - '+SITE],['Généré: '+new Date().toLocaleString()],[],['INDICATEUR','VALEUR'],['Équipements',document.getElementById('equipements').textContent],['Pannes',document.getElementById('pannes').textContent],['Interventions',document.getElementById('interventions').textContent],['Disponibilité',document.getElementById('disponibilite').textContent],['MTTR',document.getElementById('mttr').textContent],['MTBF',document.getElementById('mtbf').textContent]];
            XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(sum), 'Résumé');
            if (filteredData.length) {
                const h = ['ID','Date','Site','Équipement','Type','Priorité','Statut','Durée','Technicien'];
                const rows = filteredData.map(i => [i.id,i.date,i.site,i.equipement,i.type_panne,i.priorite,i.statut,i.duree,i.technicien]);
                XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet([h,...rows]), 'Interventions');
            }
            XLSX.writeFile(wb, `dashboard_${SITE}_${new Date().toISOString().split('T')[0]}.xlsx`);
            alert('✅ Export réussi !');
        }

        (async function() {
            await loadCards();
            if (SITE === 'GLOBAL') await loadComparison();
            await loadCharts();
            await loadTableData();
        })();

        setInterval(() => { loadCards(); if (SITE === 'GLOBAL') loadComparison(); loadCharts(); loadTableData(); }, 300000);

        if (document.body.getAttribute('data-theme') === 'system') {
            document.body.setAttribute('data-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        }
    </script>
</body>
</html>