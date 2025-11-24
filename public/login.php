<?php
session_start();

// Rediriger si déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$selected = $_SESSION['lang'] ?? 'fr';
require __DIR__ . '/../lang.php';
$t = $lang[$selected] ?? $lang['fr'];

$error = '';
$success = '';

// Traitement du formulaire de connexion par mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_type']) && $_POST['login_type'] === 'password') {
    require __DIR__ . '/../config.php';
    
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($email) || empty($password)) {
        $error = $t['error_empty_fields'] ?? 'Veuillez remplir tous les champs';
    } else {
        $stmt = $conn->prepare("SELECT id, nom, prenom, email, mot_de_passe, role FROM utilisateurs WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if ($password === $user['mot_de_passe']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_prenom'] = $user['prenom'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['logged_in'] = true;
                $_SESSION['just_logged_in'] = true;
                $_SESSION['last_activity'] = time();
                
                header('Location: dashboard.php');
                exit;
            } else {
                $error = $t['error_invalid_credentials'] ?? 'Email ou mot de passe incorrect';
            }
        } else {
            $error = $t['error_invalid_credentials'] ?? 'Email ou mot de passe incorrect';
        }
        
        $stmt->close();
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($selected) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['login'] ?? 'Connexion') ?> — DIKA MAROCCO AFRICA</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="/DIKA_equipment_dashbord_full/public/images/favicon.png">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: url('images/bg-usine.jpeg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 0;
        }

        /* Header fixe en haut */
        .header-top {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            background: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.3) 100%);
            backdrop-filter: blur(10px);
            height: 90px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-shrink: 0;
        }

        .logo-img {
            width: 60px;
            height: 60px;
            flex-shrink: 0;
            background: white;
            padding: 5px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .logo-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-text {
            flex-shrink: 0;
        }

        .brand-text h1 {
            color: white;
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.7);
            white-space: nowrap;
        }

        .brand-text p {
            color: rgba(255,255,255,0.95);
            font-size: 13px;
            margin: 0;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.7);
            white-space: nowrap;
        }

        .lang-selector {
            background: rgba(255, 255, 255, 0.95);
            padding: 10px 18px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }

        .lang-selector select {
            border: none;
            background: transparent;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            cursor: pointer;
            outline: none;
            padding: 4px 8px;
        }

        /* Container principal avec padding pour le header */
        .page-wrapper {
            min-height: 100vh;
            padding-top: 90px; /* Hauteur du header */
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 5%;
            padding-left: 20px;
            position: relative;
            z-index: 1;
        }

        .login-container {
            width: 100%;
            max-width: 480px;
            margin: 20px 0;
        }

        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: -10px 0 40px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideInRight 0.6s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .login-tabs {
            display: flex;
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        .tab-button {
            flex: 1;
            padding: 16px 8px;
            background: none;
            border: none;
            font-size: 14px;
            font-weight: 500;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            border-bottom: 3px solid transparent;
            white-space: nowrap;
        }

        .tab-button.active {
            background: white;
            color: #dc3545;
            border-bottom: 3px solid #dc3545;
        }

        .tab-button:hover:not(.active):not(.disabled) {
            background: #e9ecef;
            color: #495057;
        }

        .tab-button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .tab-content {
            display: none;
            padding: 35px 30px;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 18px;
            z-index: 2;
            pointer-events: none;
        }

        .form-control {
            padding-left: 45px;
            height: 48px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
            outline: none;
        }

        .btn-login {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .password-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 13px;
            gap: 10px;
            flex-wrap: wrap;
        }

        .password-options a {
            color: #dc3545;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .password-options a:hover {
            color: #c82333;
            text-decoration: underline;
        }

        .demo-info {
            margin-top: 25px;
            padding: 15px;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 4px;
        }

        .demo-info strong {
            display: block;
            color: #856404;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .demo-accounts {
            font-size: 12px;
            color: #856404;
            line-height: 1.8;
            font-family: 'Courier New', monospace;
        }

        .coming-soon {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .coming-soon i {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .coming-soon h3 {
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .coming-soon p {
            font-size: 14px;
            color: #adb5bd;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .page-wrapper {
                justify-content: center;
                padding-right: 20px;
            }
        }

        @media (max-width: 768px) {
            .header-top {
                height: auto;
                min-height: 90px;
                padding: 15px 20px;
                flex-wrap: wrap;
                gap: 15px;
                justify-content: center;
            }

            .brand-logo {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .brand-text h1 {
                font-size: 18px;
            }

            .brand-text p {
                font-size: 12px;
            }

            .page-wrapper {
                padding-top: 140px; /* Plus d'espace pour le header wrappé */
                padding-left: 15px;
                padding-right: 15px;
            }

            .login-container {
                max-width: 100%;
            }

            .tab-button {
                font-size: 12px;
                padding: 12px 6px;
            }

            .tab-content {
                padding: 25px 20px;
            }

            .password-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }

        @media (max-width: 480px) {
            .brand-text h1 {
                font-size: 16px;
            }

            .brand-text p {
                font-size: 11px;
            }

            .logo-img {
                width: 50px;
                height: 50px;
            }

            .tab-button {
                font-size: 11px;
                padding: 10px 4px;
            }

            .form-control {
                font-size: 13px;
            }

            .demo-accounts {
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <!-- Header fixe -->
    <div class="header-top">
        <div class="brand-logo">
            <div class="logo-img">
                <img src="images/logo.jpeg" alt="DIKA Logo">
            </div>
            <div class="brand-text">
                <h1>DIKA MAROCCO AFRICA</h1>
                <p>Département Maintenance Industrielle</p>
            </div>
        </div>
        <div class="lang-selector">
            <select onchange="if(this.value) window.location.href='change_lang.php?lang='+this.value+'&redirect=login'">
                <option value="">-- <?= htmlspecialchars($t['language'] ?? 'Langue') ?> --</option>
                <option value="fr" <?= $selected === 'fr' ? 'selected' : '' ?>>Français</option>
                <option value="en" <?= $selected === 'en' ? 'selected' : '' ?>>English</option>
                <option value="cn" <?= $selected === 'cn' ? 'selected' : '' ?>>中文</option>
            </select>
        </div>
    </div>

    <!-- Page wrapper avec padding pour le header -->
    <div class="page-wrapper">
        <div class="login-container">
            <div class="login-card">
                <!-- Tabs -->
                <div class="login-tabs">
                    <button class="tab-button active" onclick="switchTab('password')">
                        <?= htmlspecialchars($t['password_login'] ?? 'Password Login') ?>
                    </button>
                    <button class="tab-button disabled" title="<?= htmlspecialchars($t['coming_soon'] ?? 'Bientôt disponible') ?>">
                        <?= htmlspecialchars($t['ca_login'] ?? 'CA Login') ?>
                    </button>
                    <button class="tab-button disabled" title="<?= htmlspecialchars($t['coming_soon'] ?? 'Bientôt disponible') ?>">
                        <?= htmlspecialchars($t['sms_login'] ?? 'SMS Login') ?>
                    </button>
                </div>

                <!-- Password Login Tab -->
                <div id="password-tab" class="tab-content active">
                    <?php if ($error): ?>
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div><?= htmlspecialchars($error) ?></div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <input type="hidden" name="login_type" value="password">
                        
                        <div class="form-group">
                            <label class="form-label"><?= htmlspecialchars($t['email'] ?? 'Email') ?></label>
                            <div class="input-group">
                                <i class="bi bi-envelope input-icon"></i>
                                <input 
                                    type="email" 
                                    name="email" 
                                    class="form-control" 
                                    placeholder="exemple@dika.ma"
                                    required
                                    autocomplete="email"
                                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label"><?= htmlspecialchars($t['password'] ?? 'Mot de passe') ?></label>
                            <div class="input-group">
                                <i class="bi bi-lock input-icon"></i>
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="form-control" 
                                    placeholder="<?= htmlspecialchars($t['password'] ?? 'Mot de passe') ?>"
                                    required
                                    autocomplete="current-password"
                                >
                            </div>
                        </div>

                        <div class="password-options">
                            <a href="#" onclick="alert('<?= htmlspecialchars($t['coming_soon'] ?? 'Fonctionnalité en développement') ?>'); return false;">
                                <?= htmlspecialchars($t['reset_password'] ?? 'Réinitialiser le mot de passe') ?>
                            </a>
                            <a href="#" onclick="alert('<?= htmlspecialchars($t['coming_soon'] ?? 'Fonctionnalité en développement') ?>'); return false;">
                                <?= htmlspecialchars($t['forgot_password'] ?? 'Mot de passe oublié?') ?>
                            </a>
                        </div>

                        <button type="submit" class="btn btn-login">
                            <?= htmlspecialchars($t['login_btn'] ?? 'Connexion') ?>
                        </button>
                    </form>

                    <div class="demo-info">
                        <strong><i class="bi bi-info-circle"></i> <?= htmlspecialchars($t['demo_accounts'] ?? 'Comptes de démonstration') ?> :</strong>
                        <div class="demo-accounts">
                            <strong>Admin:</strong> Youness.filali@dika.com / admin123<br>
                            <strong>Technicien:</strong> ahmed.bernoussi@dika.com / tech123<br>
                            <strong>Responsable:</strong> laila.essakhi@dika.com / resp123
                        </div>
                    </div>
                </div>

                <!-- CA Login Tab -->
                <div id="ca-tab" class="tab-content">
                    <div class="coming-soon">
                        <i class="bi bi-shield-lock"></i>
                        <h3><?= htmlspecialchars($t['ca_login'] ?? 'Connexion par Certificat') ?></h3>
                        <p><?= htmlspecialchars($t['coming_soon'] ?? 'Cette fonctionnalité sera bientôt disponible') ?></p>
                    </div>
                </div>

                <!-- SMS Login Tab -->
                <div id="sms-tab" class="tab-content">
                    <div class="coming-soon">
                        <i class="bi bi-phone"></i>
                        <h3><?= htmlspecialchars($t['sms_login'] ?? 'Connexion par SMS') ?></h3>
                        <p><?= htmlspecialchars($t['coming_soon'] ?? 'Cette fonctionnalité sera bientôt disponible') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function switchTab(tabName) {
            // Ne pas permettre de switcher vers les tabs désactivés
            if (tabName === 'ca' || tabName === 'sms') {
                return;
            }

            // Retirer la classe active de tous les boutons et contenus
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            // Ajouter la classe active au bouton et contenu sélectionné
            const buttons = document.querySelectorAll('.tab-button');
            buttons.forEach(btn => {
                if (btn.onclick && btn.onclick.toString().includes(tabName)) {
                    btn.classList.add('active');
                }
            });
            
            document.getElementById(`${tabName}-tab`).classList.add('active');
        }
    </script>
</body>
</html>