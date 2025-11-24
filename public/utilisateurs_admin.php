<?php
// Protection de la page
require __DIR__ . '/auth_check.php';

$selected = $_SESSION['lang'] ?? 'fr';
require __DIR__ . '/../lang.php';
$t = $lang[$selected] ?? $lang['fr'];

$user_name = $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'];
$user_role = $_SESSION['user_role'];
$theme = $_SESSION['theme'] ?? 'system';

// Traitement des actions (Ajout, Modification, Suppression)
require __DIR__ . '/../config.php';

$message = '';
$message_type = '';

// Suppression
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = $t['user_deleted'] ?? 'Utilisateur supprimé avec succès';
        $message_type = "success";
    } else {
        $message = $t['error_delete'];
        $message_type = "danger";
    }
    $stmt->close();
}

// Ajout ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];
    $role = $_POST['role'];
    
    if ($id) {
        // Modification
        if (!empty($mot_de_passe)) {
            $stmt = $conn->prepare("UPDATE utilisateurs SET nom=?, prenom=?, email=?, mot_de_passe=?, role=? WHERE id=?");
            $stmt->bind_param("sssssi", $nom, $prenom, $email, $mot_de_passe, $role, $id);
        } else {
            $stmt = $conn->prepare("UPDATE utilisateurs SET nom=?, prenom=?, email=?, role=? WHERE id=?");
            $stmt->bind_param("ssssi", $nom, $prenom, $email, $role, $id);
        }
        $success_msg = $t['user_modified'] ?? 'Utilisateur modifié avec succès';
    } else {
        // Ajout
        $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nom, $prenom, $email, $mot_de_passe, $role);
        $success_msg = $t['user_added'] ?? 'Utilisateur ajouté avec succès';
    }
    
    if ($stmt->execute()) {
        $message = $success_msg;
        $message_type = "success";
    } else {
        $message = $t['error_operation'];
        $message_type = "danger";
    }
    $stmt->close();
}

// Récupération de tous les utilisateurs
$utilisateurs = [];
$result = $conn->query("SELECT * FROM utilisateurs ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    $utilisateurs[] = $row;
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($selected) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['user_management'] ?? 'Gestion des Utilisateurs') ?> - DIKA</title>
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
        .table-container {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
            max-height: 600px;
            overflow-y: auto;
            position: relative;
        }
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
        .data-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 13px;
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
        .badge-admin {
            background: rgba(220, 38, 38, 0.15);
            color: #b91c1c;
            border: 1px solid rgba(220, 38, 38, 0.3);
        }
        .badge-responsable {
            background: rgba(245, 158, 11, 0.15);
            color: #b45309;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        .badge-technicien {
            background: rgba(5, 150, 105, 0.15);
            color: #047857;
            border: 1px solid rgba(5, 150, 105, 0.3);
        }
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
                        <h1><?= htmlspecialchars($t['brand'] ?? 'DIKA') ?></h1>
                        <p><?= htmlspecialchars($t['dept'] ?? 'Maintenance') ?></p>
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
                    <a href="change_lang.php?lang=fr&redirect=utilisateurs_admin" class="<?= $selected === 'fr' ? 'active' : '' ?>">FR</a>
                    <a href="change_lang.php?lang=en&redirect=utilisateurs_admin" class="<?= $selected === 'en' ? 'active' : '' ?>">EN</a>
                    <a href="change_lang.php?lang=cn&redirect=utilisateurs_admin" class="<?= $selected === 'cn' ? 'active' : '' ?>">中文</a>
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
                    <a href="pannes_admin.php" class="menu-link">
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
                <li class="menu-item">
                    <a href="utilisateurs_admin.php" class="menu-link active">
                        <span class="menu-icon"><i class="fa-solid fa-users"></i></span>
                        <span class="menu-text"><?= htmlspecialchars($t['manage_users'] ?? 'Utilisateurs') ?></span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="breadcrumb-custom">
                <i class="bi bi-house-door"></i> <a href="dashboard.php"><?= htmlspecialchars($t['home'] ?? 'Accueil') ?></a> > <?= htmlspecialchars($t['user_management'] ?? 'Gestion des Utilisateurs') ?>
            </div>

            <?php if ($message): ?>
            <div class="alert alert-<?= $message_type ?> alert-dismissible fade show">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Action Bar -->
            <div class="action-bar">
                <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalUser" onclick="resetForm()">
                    <i class="bi bi-plus-circle"></i>
                    <?= htmlspecialchars($t['add_user'] ?? 'Ajouter un utilisateur') ?>
                </button>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><?= htmlspecialchars($t['id'] ?? 'ID') ?></th>
                            <th><?= htmlspecialchars($t['last_name'] ?? 'Nom') ?></th>
                            <th><?= htmlspecialchars($t['first_name'] ?? 'Prénom') ?></th>
                            <th><?= htmlspecialchars($t['email']) ?></th>
                            <th><?= htmlspecialchars($t['role'] ?? 'Rôle') ?></th>
                            <th><?= htmlspecialchars($t['actions'] ?? 'Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($utilisateurs)): ?>
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 40px;">
                                <i class="bi bi-people" style="font-size: 48px; color: var(--text-secondary); opacity: 0.5;"></i>
                                <p style="color: var(--text-secondary); margin-top: 10px;"><?= htmlspecialchars($t['no_user_found'] ?? 'Aucun utilisateur trouvé') ?></p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($utilisateurs as $user): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($user['id']) ?></strong></td>
                                <td><?= htmlspecialchars($user['nom']) ?></td>
                                <td><?= htmlspecialchars($user['prenom']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <span class="badge badge-<?= htmlspecialchars($user['role']) ?>">
                                        <?= htmlspecialchars(ucfirst($user['role'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-action btn-edit" onclick="editUser(<?= htmlspecialchars(json_encode($user, JSON_HEX_APOS | JSON_HEX_QUOT)) ?>)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn-action btn-delete" onclick="if(confirm('<?= htmlspecialchars($t['confirm_delete'] ?? 'Êtes-vous sûr de vouloir supprimer cet utilisateur ?') ?>')) window.location.href='?delete=<?= $user['id'] ?>'">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalUser" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"><?= htmlspecialchars($t['add_user_title'] ?? 'Ajouter un utilisateur') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="userId">
                        <div class="mb-3">
                            <label class="form-label"><?= htmlspecialchars($t['last_name'] ?? 'Nom') ?></label>
                            <input type="text" class="form-control" name="nom" id="userNom" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= htmlspecialchars($t['first_name'] ?? 'Prénom') ?></label>
                            <input type="text" class="form-control" name="prenom" id="userPrenom" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= htmlspecialchars($t['email']) ?></label>
                            <input type="email" class="form-control" name="email" id="userEmail" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= htmlspecialchars($t['password']) ?></label>
                            <input type="password" class="form-control" name="mot_de_passe" id="userPassword">
                            <small class="form-text text-muted" id="passwordHelp" style="display:none; color: var(--text-secondary);">
                                <?= htmlspecialchars($t['leave_empty_keep'] ?? 'Laisser vide pour conserver le mot de passe actuel') ?>
                            </small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= htmlspecialchars($t['role'] ?? 'Rôle') ?></label>
                            <select class="form-select" name="role" id="userRole" required>
                                <option value="admin"><?= htmlspecialchars($t['admin'] ?? 'Admin') ?></option>
                                <option value="responsable"><?= htmlspecialchars($t['manager'] ?? 'Responsable') ?></option>
                                <option value="technicien"><?= htmlspecialchars($t['technician'] ?? 'Technicien') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= htmlspecialchars($t['cancel'] ?? 'Annuler') ?></button>
                        <button type="submit" class="btn btn-primary" style="background: var(--primary); border-color: var(--primary);">
                            <?= htmlspecialchars($t['save'] ?? 'Enregistrer') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts
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

            setActiveMenuItem();
        });

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

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

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

        function resetForm() {
            document.getElementById('modalTitle').textContent = '<?= addslashes($t['add_user_title'] ?? 'Ajouter un utilisateur') ?>';
            document.getElementById('userId').value = '';
            document.getElementById('userNom').value = '';
            document.getElementById('userPrenom').value = '';
            document.getElementById('userEmail').value = '';
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').required = true;
            document.getElementById('userRole').value = 'technicien';
            document.getElementById('passwordHelp').style.display = 'none';
        }

        function editUser(user) {
            document.getElementById('modalTitle').textContent = '<?= addslashes($t['edit_user_title'] ?? 'Modifier un utilisateur') ?>';
            document.getElementById('userId').value = user.id;
            document.getElementById('userNom').value = user.nom;
            document.getElementById('userPrenom').value = user.prenom;
            document.getElementById('userEmail').value = user.email;
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').required = false;
            document.getElementById('userRole').value = user.role;
            document.getElementById('passwordHelp').style.display = 'block';

            var modal = new bootstrap.Modal(document.getElementById('modalUser'));
            modal.show();
        }
    </script>
</body>
</html>