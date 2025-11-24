<?php
// Définir la langue par défaut si elle n'est pas définie
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; // Anglais par défaut
}

$lang = [
    'fr' => [
        // Général
        'brand' => 'DIKA MAROCCO AFRICA',
        'dept' => 'Département Maintenance Industrielle',
        'language' => 'Langue',
        'tagline' => 'Sélectionnez une usine pour voir ses indicateurs',
        
        // Navigation
        'go_dma1' => "Accéder à l'usine DMA1",
        'go_dma2' => "Accéder à l'usine DMA2",
        'go_global' => 'Tableau de bord global',
        
        // Login
        'login' => 'Connexion',
        'email' => 'Email',
        'password' => 'Mot de passe',
        'login_btn' => 'Connexion',
        'password_login' => 'Connexion par mot de passe',
        'ca_login' => 'Connexion par certificat',
        'sms_login' => 'Connexion par SMS',
        'reset_password' => 'Réinitialiser le mot de passe',
        'forgot_password' => 'Mot de passe oublié ?',
        'demo_accounts' => 'Comptes de démonstration',
        'coming_soon' => 'Bientôt disponible',
        
        // Messages
        'welcome' => 'Bienvenue',
        'error_empty_fields' => 'Veuillez remplir tous les champs',
        'error_invalid_credentials' => 'Email ou mot de passe incorrect',
        'logout' => 'Déconnexion',
        'logged_in_as' => 'Connecté en tant que',
        
        // Dashboard
        'dashboard' => 'Tableau de bord',
        'profile' => 'Profil',
        'settings' => 'Paramètres',
        
        // Menus
        'manage_equipment' => 'Gérer Équipements',
        'manage_breakdowns' => 'Gérer Pannes',
        'manage_interventions' => 'Gérer Interventions',
        'manage_users' => 'Gérer Utilisateurs',
        
        // Stats Cards
        'total_equipment' => 'Total Équipements',
        'ongoing_breakdowns' => 'Pannes en Cours',
        'ongoing_interventions' => 'Interventions en Cours',
        'availability' => 'Disponibilité',
        
        // Charts
        'daily_week' => 'Par Jour (Semaine)',
        'weekly_year' => 'Par Semaine (Année)',
        'monthly_year' => 'Par Mois (Année)',
        'interventions_day' => 'Interventions (jour)',
        'interventions_week' => 'Interventions (semaine)',
        'interventions_month' => 'Interventions (mois)',
        
        // Breadcrumb
        'home' => 'Accueil',
        'global_dashboard' => 'Dashboard Global',
        'factory' => 'Usine',
        'equipment_management' => 'Gestion des Équipements',
        'breakdown_management' => 'Gestion des Pannes',
        'intervention_management' => 'Gestion des Interventions',
        
        // Actions & Buttons
        'add_equipment' => 'Ajouter un équipement',
        'add_breakdown' => 'Ajouter une panne',
        'add_intervention' => 'Ajouter une intervention',
        'edit' => 'Modifier',
        'delete' => 'Supprimer',
        'save' => 'Enregistrer',
        'cancel' => 'Annuler',
        'confirm_delete' => 'Confirmer la suppression ?',
        
        // Filters
        'filter_by_factory' => 'Filtrer par usine',
        'filter' => 'Filtrer',
        'all_factories' => 'Toutes les usines',
        'all_priorities' => 'Toutes priorités',
        'all_statuses' => 'Tous statuts',
        'all_types' => 'Tous les types',
        
        // Table Headers - Equipment
        'id' => 'ID',
        'name' => 'Nom',
        'reference' => 'Référence',
        'commission_date' => 'Date Mise en Service',
        'state' => 'État',
        'actions' => 'Actions',
        
        // Table Headers - Breakdowns
        'equipment' => 'Équipement',
        'breakdown_type' => 'Type de panne',
        'description' => 'Description',
        'start_date' => 'Début',
        'end_date' => 'Fin',
        'priority' => 'Priorité',
        'status' => 'Statut',
        
        // Table Headers - Interventions
        'breakdown' => 'Panne',
        'technician' => 'Technicien',
        'type' => 'Type',
        'importance' => 'Importance',
        
        // Equipment States
        'in_service' => 'En service',
        'stopped' => 'Arrêté',
        'maintenance' => 'Maintenance',
        
        // Priorities
        'low' => 'Basse',
        'medium' => 'Moyenne',
        'high' => 'Haute',
        'critical' => 'Critique',
        
        // Status
        'ongoing' => 'En cours',
        'completed' => 'Terminée',
        'pending' => 'En attente',
        
        // Intervention Types
        'preventive' => 'Préventive',
        'corrective' => 'Corrective',
        
        // Importance
        'normal' => 'Normale',
        'important' => 'Importante',
        
        // Form Labels
        'select_equipment' => '-- Sélectionner --',
        'optional_breakdown' => 'Panne associée (optionnel)',
        'no_breakdown' => 'Sans panne (maintenance préventive)',
        'select_technician' => '-- Sélectionner --',
        'start_datetime' => 'Date/Heure de début',
        'end_datetime' => 'Date/Heure de fin',
        
        // Modal Titles
        'add_equipment_title' => 'Ajouter un équipement',
        'edit_equipment_title' => 'Modifier un équipement',
        'add_breakdown_title' => 'Ajouter une panne',
        'edit_breakdown_title' => 'Modifier une panne',
        'add_intervention_title' => 'Ajouter une intervention',
        'edit_intervention_title' => 'Modifier une intervention',
        
        // Messages
        'no_equipment_found' => 'Aucun équipement trouvé',
        'no_breakdown_found' => 'Aucune panne trouvée',
        'no_intervention_found' => 'Aucune intervention trouvée',
        'equipment_deleted' => 'Équipement supprimé avec succès',
        'breakdown_deleted' => 'Panne supprimée avec succès',
        'intervention_deleted' => 'Intervention supprimée avec succès',
        'equipment_added' => 'Équipement ajouté avec succès',
        'breakdown_added' => 'Panne ajoutée avec succès',
        'intervention_added' => 'Intervention ajoutée avec succès',
        'equipment_modified' => 'Équipement modifié avec succès',
        'breakdown_modified' => 'Panne modifiée avec succès',
        'intervention_modified' => 'Intervention modifiée avec succès',
        'error_operation' => "Erreur lors de l'opération",
        'error_delete' => 'Erreur lors de la suppression',

        // Stats Cards
        'total_equipment' => 'Total Équipements',
        'machines_in_fleet' => 'Machines en parc',
        'ongoing_breakdowns' => 'Pannes en cours',
        'require_intervention' => 'Nécessitent intervention',
        'interventions' => 'Interventions',
        'active' => 'Actives',
        'availability' => 'Disponibilité',
        'operational_rate' => 'Taux opérationnel',
        'mean_repair_time' => 'Temps moyen réparation',
        'time_between_failures' => 'Temps entre pannes',
        
        // Sections
        'comparison_dma' => 'Comparaison DMA1 vs DMA2',
        'period_filters' => 'Filtres de Période',
        'evolution_charts' => "Graphiques d'Évolution",
        'detailed_data' => 'Données Détaillées',
        'details' => 'Détails',
        'best' => 'Meilleur',
        
        // Filtres
        'period' => 'Période',
        'start_date' => 'Date de début',
        'end_date' => 'Date de fin',
        'last_7_days' => '7 derniers jours',
        'last_14_days' => '14 derniers jours',
        'last_30_days' => '30 derniers jours',
        'last_90_days' => '90 derniers jours',
        'apply' => 'Appliquer',
        'reset' => 'Réinitialiser',
        
        // Tableau
        'search' => 'Rechercher',
        'all' => 'Tous',
        'today' => "Aujourd'hui",
        'this_week' => 'Cette semaine',
        'this_month' => 'Ce mois',
        'this_year' => 'Cette année',
        'site' => 'Site',
        'status' => 'Statut',
        'priority' => 'Priorité',
        'refresh' => 'Actualiser',
        'export' => 'Exporter',
        'per_page' => 'Par page',
        'display_of' => 'Affichage de',
        'results' => 'résultats',
        'page' => 'Page',
        'of' => 'sur',
        'first' => 'Premier',
        'previous' => 'Précédent',
        'next' => 'Suivant',
        'last' => 'Dernier',
        'no_data' => 'Aucune donnée',
        'loading' => 'Chargement...',
        
        // Statuts
        'in_progress' => 'En cours',
        'completed' => 'Terminé',
        'pending' => 'En attente',
        
        // Priorités
        'critical' => 'Critique',
        'high' => 'Haute',
        'medium' => 'Moyenne',
        'low' => 'Basse',
        
        // Graphiques
        'evolution' => 'Évolution',
        'top_10_equipment' => 'Top 10 Équipements',
        'comparison_7_days' => 'Comparaison DMA1 vs DMA2 (7 jours)',
        
        // Export
        'export_success' => 'Export réussi !',
    ],
    
    'en' => [
        // General
        'brand' => 'DIKA MAROCCO AFRICA',
        'dept' => 'Industrial Maintenance Department',
        'language' => 'Language',
        'tagline' => 'Select a plant to view its KPIs',
        
        // Navigation
        'go_dma1' => 'Go to Factory DMA1',
        'go_dma2' => 'Go to Factory DMA2',
        'go_global' => 'Global Dashboard',
        
        // Login
        'login' => 'Login',
        'email' => 'Email',
        'password' => 'Password',
        'login_btn' => 'Login',
        'password_login' => 'Password Login',
        'ca_login' => 'CA Login',
        'sms_login' => 'SMS Login',
        'reset_password' => 'Reset password',
        'forgot_password' => 'Forgot password?',
        'demo_accounts' => 'Demo accounts',
        'coming_soon' => 'Coming soon',
        
        // Messages
        'welcome' => 'Welcome',
        'error_empty_fields' => 'Please fill in all fields',
        'error_invalid_credentials' => 'Invalid email or password',
        'logout' => 'Logout',
        'logged_in_as' => 'Logged in as',
        
        // Dashboard
        'dashboard' => 'Dashboard',
        'profile' => 'Profile',
        'settings' => 'Settings',
        
        // Menus
        'manage_equipment' => 'Manage Equipment',
        'manage_breakdowns' => 'Manage Breakdowns',
        'manage_interventions' => 'Manage Interventions',
        'manage_users' => 'Manage Users',
        
        // Stats Cards
        'total_equipment' => 'Total Equipment',
        'ongoing_breakdowns' => 'Ongoing Breakdowns',
        'ongoing_interventions' => 'Ongoing Interventions',
        'availability' => 'Availability',
        
        // Charts
        'daily_week' => 'Daily (Week)',
        'weekly_year' => 'Weekly (Year)',
        'monthly_year' => 'Monthly (Year)',
        'interventions_day' => 'Interventions (daily)',
        'interventions_week' => 'Interventions (weekly)',
        'interventions_month' => 'Interventions (monthly)',
        
        // Breadcrumb
        'home' => 'Home',
        'global_dashboard' => 'Global Dashboard',
        'factory' => 'Factory',
        'equipment_management' => 'Equipment Management',
        'breakdown_management' => 'Breakdown Management',
        'intervention_management' => 'Intervention Management',
        
        // Actions & Buttons
        'add_equipment' => 'Add Equipment',
        'add_breakdown' => 'Add Breakdown',
        'add_intervention' => 'Add Intervention',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'confirm_delete' => 'Confirm deletion?',
        
        // Filters
        'filter_by_factory' => 'Filter by factory',
        'filter' => 'Filter',
        'all_factories' => 'All factories',
        'all_priorities' => 'All priorities',
        'all_statuses' => 'All statuses',
        'all_types' => 'All types',
        
        // Table Headers - Equipment
        'id' => 'ID',
        'name' => 'Name',
        'reference' => 'Reference',
        'commission_date' => 'Commission Date',
        'state' => 'State',
        'actions' => 'Actions',
        
        // Table Headers - Breakdowns
        'equipment' => 'Equipment',
        'breakdown_type' => 'Breakdown Type',
        'description' => 'Description',
        'start_date' => 'Start',
        'end_date' => 'End',
        'priority' => 'Priority',
        'status' => 'Status',
        
        // Table Headers - Interventions
        'breakdown' => 'Breakdown',
        'technician' => 'Technician',
        'type' => 'Type',
        'importance' => 'Importance',
        
        // Equipment States
        'in_service' => 'In Service',
        'stopped' => 'Stopped',
        'maintenance' => 'Maintenance',
        
        // Priorities
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'critical' => 'Critical',
        
        // Status
        'ongoing' => 'Ongoing',
        'completed' => 'Completed',
        'pending' => 'Pending',
        
        // Intervention Types
        'preventive' => 'Preventive',
        'corrective' => 'Corrective',
        
        // Importance
        'normal' => 'Normal',
        'important' => 'Important',
        
        // Form Labels
        'select_equipment' => '-- Select --',
        'optional_breakdown' => 'Associated breakdown (optional)',
        'no_breakdown' => 'No breakdown (preventive maintenance)',
        'select_technician' => '-- Select --',
        'start_datetime' => 'Start Date/Time',
        'end_datetime' => 'End Date/Time',
        
        // Modal Titles
        'add_equipment_title' => 'Add Equipment',
        'edit_equipment_title' => 'Edit Equipment',
        'add_breakdown_title' => 'Add Breakdown',
        'edit_breakdown_title' => 'Edit Breakdown',
        'add_intervention_title' => 'Add Intervention',
        'edit_intervention_title' => 'Edit Intervention',
        
        // Messages
        'no_equipment_found' => 'No equipment found',
        'no_breakdown_found' => 'No breakdown found',
        'no_intervention_found' => 'No intervention found',
        'equipment_deleted' => 'Equipment deleted successfully',
        'breakdown_deleted' => 'Breakdown deleted successfully',
        'intervention_deleted' => 'Intervention deleted successfully',
        'equipment_added' => 'Equipment added successfully',
        'breakdown_added' => 'Breakdown added successfully',
        'intervention_added' => 'Intervention added successfully',
        'equipment_modified' => 'Equipment modified successfully',
        'breakdown_modified' => 'Breakdown modified successfully',
        'intervention_modified' => 'Intervention modified successfully',
        'error_operation' => 'Error during operation',
        'error_delete' => 'Error during deletion',

        // Stats Cards
        'total_equipment' => 'Total Equipment',
        'machines_in_fleet' => 'Machines in fleet',
        'ongoing_breakdowns' => 'Ongoing Breakdowns',
        'require_intervention' => 'Require intervention',
        'interventions' => 'Interventions',
        'active' => 'Active',
        'availability' => 'Availability',
        'operational_rate' => 'Operational rate',
        'mean_repair_time' => 'Mean repair time',
        'time_between_failures' => 'Time between failures',
        
        // Sections
        'comparison_dma' => 'DMA1 vs DMA2 Comparison',
        'period_filters' => 'Period Filters',
        'evolution_charts' => 'Evolution Charts',
        'detailed_data' => 'Detailed Data',
        'details' => 'Details',
        'best' => 'Best',
        
        // Filters
        'period' => 'Period',
        'start_date' => 'Start date',
        'end_date' => 'End date',
        'last_7_days' => 'Last 7 days',
        'last_14_days' => 'Last 14 days',
        'last_30_days' => 'Last 30 days',
        'last_90_days' => 'Last 90 days',
        'apply' => 'Apply',
        'reset' => 'Reset',
        
        // Table
        'search' => 'Search',
        'all' => 'All',
        'today' => 'Today',
        'this_week' => 'This week',
        'this_month' => 'This month',
        'this_year' => 'This year',
        'site' => 'Site',
        'status' => 'Status',
        'priority' => 'Priority',
        'refresh' => 'Refresh',
        'export' => 'Export',
        'per_page' => 'Per page',
        'display_of' => 'Showing',
        'results' => 'results',
        'page' => 'Page',
        'of' => 'of',
        'first' => 'First',
        'previous' => 'Previous',
        'next' => 'Next',
        'last' => 'Last',
        'no_data' => 'No data',
        'loading' => 'Loading...',
        
        // Statuses
        'in_progress' => 'In progress',
        'completed' => 'Completed',
        'pending' => 'Pending',
        
        // Priorities
        'critical' => 'Critical',
        'high' => 'High',
        'medium' => 'Medium',
        'low' => 'Low',
        
        // Charts
        'evolution' => 'Evolution',
        'top_10_equipment' => 'Top 10 Equipment',
        'comparison_7_days' => 'DMA1 vs DMA2 Comparison (7 days)',
        
        // Export
        'export_success' => 'Export successful!',
    ],
    
    'cn' => [
        // 一般
        'brand' => 'DIKA MAROCCO AFRICA',
        'dept' => '工业维护部门',
        'language' => '语言',
        'tagline' => '请选择工厂以查看指标',
        
        // 导航
        'go_dma1' => '进入 DMA1 工厂',
        'go_dma2' => '进入 DMA2 工厂',
        'go_global' => '全局仪表板',
        
        // 登录
        'login' => '登录',
        'email' => '邮箱',
        'password' => '密码',
        'login_btn' => '登录',
        'password_login' => '密码登录',
        'ca_login' => '证书登录',
        'sms_login' => '短信登录',
        'reset_password' => '重置密码',
        'forgot_password' => '忘记密码？',
        'demo_accounts' => '演示账户',
        'coming_soon' => '即将推出',
        
        // 消息
        'welcome' => '欢迎',
        'error_empty_fields' => '请填写所有字段',
        'error_invalid_credentials' => '邮箱或密码错误',
        'logout' => '登出',
        'logged_in_as' => '当前登录',
        
        // 仪表板
        'dashboard' => '仪表板',
        'profile' => '个人资料',
        'settings' => '设置',
        
        // 菜单
        'manage_equipment' => '管理设备',
        'manage_breakdowns' => '管理故障',
        'manage_interventions' => '管理干预',
        'manage_users' => '管理用户',
        
        // 统计卡片
        'total_equipment' => '设备总数',
        'ongoing_breakdowns' => '进行中的故障',
        'ongoing_interventions' => '进行中的干预',
        'availability' => '可用性',
        
        // 图表
        'daily_week' => '每日（周）',
        'weekly_year' => '每周（年）',
        'monthly_year' => '每月（年）',
        'interventions_day' => '干预（每日）',
        'interventions_week' => '干预（每周）',
        'interventions_month' => '干预（每月）',
        
        // 面包屑
        'home' => '首页',
        'global_dashboard' => '全局仪表板',
        'factory' => '工厂',
        'equipment_management' => '设备管理',
        'breakdown_management' => '故障管理',
        'intervention_management' => '干预管理',
        
        // 操作和按钮
        'add_equipment' => '添加设备',
        'add_breakdown' => '添加故障',
        'add_intervention' => '添加干预',
        'edit' => '编辑',
        'delete' => '删除',
        'save' => '保存',
        'cancel' => '取消',
        'confirm_delete' => '确认删除？',
        
        // 筛选
        'filter_by_factory' => '按工厂筛选',
        'filter' => '筛选',
        'all_factories' => '所有工厂',
        'all_priorities' => '所有优先级',
        'all_statuses' => '所有状态',
        'all_types' => '所有类型',
        
        // 表格标题 - 设备
        'id' => 'ID',
        'name' => '名称',
        'reference' => '参考',
        'commission_date' => '投入使用日期',
        'state' => '状态',
        'actions' => '操作',
        
        // 表格标题 - 故障
        'equipment' => '设备',
        'breakdown_type' => '故障类型',
        'description' => '描述',
        'start_date' => '开始',
        'end_date' => '结束',
        'priority' => '优先级',
        'status' => '状态',
        
        // 表格标题 - 干预
        'breakdown' => '故障',
        'technician' => '技术员',
        'type' => '类型',
        'importance' => '重要性',
        
        // 设备状态
        'in_service' => '使用中',
        'stopped' => '停止',
        'maintenance' => '维护中',
        
        // 优先级
        'low' => '低',
        'medium' => '中',
        'high' => '高',
        'critical' => '紧急',
        
        // 状态
        'ongoing' => '进行中',
        'completed' => '已完成',
        'pending' => '等待中',
        
        // 干预类型
        'preventive' => '预防性',
        'corrective' => '纠正性',
        
        // 重要性
        'normal' => '正常',
        'important' => '重要',
        
        // 表单标签
        'select_equipment' => '-- 选择 --',
        'optional_breakdown' => '关联故障（可选）',
        'no_breakdown' => '无故障（预防性维护）',
        'select_technician' => '-- 选择 --',
        'start_datetime' => '开始日期/时间',
        'end_datetime' => '结束日期/时间',
        
        // 模态框标题
        'add_equipment_title' => '添加设备',
        'edit_equipment_title' => '编辑设备',
        'add_breakdown_title' => '添加故障',
        'edit_breakdown_title' => '编辑故障',
        'add_intervention_title' => '添加干预',
        'edit_intervention_title' => '编辑干预',
        
        // 消息
        'no_equipment_found' => '未找到设备',
        'no_breakdown_found' => '未找到故障',
        'no_intervention_found' => '未找到干预',
        'equipment_deleted' => '设备删除成功',
        'breakdown_deleted' => '故障删除成功',
        'intervention_deleted' => '干预删除成功',
        'equipment_added' => '设备添加成功',
        'breakdown_added' => '故障添加成功',
        'intervention_added' => '干预添加成功',
        'equipment_modified' => '设备修改成功',
        'breakdown_modified' => '故障修改成功',
        'intervention_modified' => '干预修改成功',
        'error_operation' => '操作错误',
        'error_delete' => '删除错误',

        // FRANÇAIS
        'user_management' => 'Gestion des Utilisateurs',
        'add_user' => 'Ajouter un utilisateur',
        'add_user_title' => 'Ajouter un utilisateur',
        'edit_user_title' => 'Modifier un utilisateur',
        'user_added' => 'Utilisateur ajouté avec succès',
        'user_modified' => 'Utilisateur modifié avec succès',
        'user_deleted' => 'Utilisateur supprimé avec succès',
        'no_user_found' => 'Aucun utilisateur trouvé',
        'last_name' => 'Nom',
        'first_name' => 'Prénom',
        'role' => 'Rôle',
        'leave_empty_keep' => 'Laisser vide pour conserver le mot de passe actuel',

        // ENGLISH
        'user_management' => 'User Management',
        'add_user' => 'Add User',
        'add_user_title' => 'Add User',
        'edit_user_title' => 'Edit User',
        'user_added' => 'User added successfully',
        'user_modified' => 'User modified successfully',
        'user_deleted' => 'User deleted successfully',
        'no_user_found' => 'No user found',
        'last_name' => 'Last Name',
        'first_name' => 'First Name',
        'role' => 'Role',
        'leave_empty_keep' => 'Leave empty to keep current password',

        // CHINESE (中文)
        'user_management' => '用户管理',
        'add_user' => '添加用户',
        'add_user_title' => '添加用户',
        'edit_user_title' => '编辑用户',
        'user_added' => '用户添加成功',
        'user_modified' => '用户修改成功',
        'user_deleted' => '用户删除成功',
        'no_user_found' => '未找到用户',
        'last_name' => '姓',
        'first_name' => '名',
        'role' => '角色',
        'leave_empty_keep' => '留空以保留当前密码',
        // 统计卡片
        'total_equipment' => '设备总数',
        'machines_in_fleet' => '机器数量',
        'ongoing_breakdowns' => '当前故障',
        'require_intervention' => '需要干预',
        'interventions' => '干预',
        'active' => '活跃',
        'availability' => '可用性',
        'operational_rate' => '运营率',
        'mean_repair_time' => '平均修复时间',
        'time_between_failures' => '故障间隔时间',
        
        // 区域
        'comparison_dma' => 'DMA1与DMA2比较',
        'period_filters' => '时间过滤器',
        'evolution_charts' => '演变图表',
        'detailed_data' => '详细数据',
        'details' => '详情',
        'best' => '最佳',
        
        // 过滤器
        'period' => '时间段',
        'start_date' => '开始日期',
        'end_date' => '结束日期',
        'last_7_days' => '最近7天',
        'last_14_days' => '最近14天',
        'last_30_days' => '最近30天',
        'last_90_days' => '最近90天',
        'apply' => '应用',
        'reset' => '重置',
        
        // 表格
        'search' => '搜索',
        'all' => '全部',
        'today' => '今天',
        'this_week' => '本周',
        'this_month' => '本月',
        'this_year' => '今年',
        'site' => '站点',
        'status' => '状态',
        'priority' => '优先级',
        'refresh' => '刷新',
        'export' => '导出',
        'per_page' => '每页',
        'display_of' => '显示',
        'results' => '结果',
        'page' => '页',
        'of' => '共',
        'first' => '首页',
        'previous' => '上一页',
        'next' => '下一页',
        'last' => '末页',
        'no_data' => '无数据',
        'loading' => '加载中...',
        
        // 状态
        'in_progress' => '进行中',
        'completed' => '已完成',
        'pending' => '待处理',
        
        // 优先级
        'critical' => '紧急',
        'high' => '高',
        'medium' => '中',
        'low' => '低',
        
        // 图表
        'evolution' => '演变',
        'top_10_equipment' => '前10设备',
        'comparison_7_days' => 'DMA1与DMA2比较(7天)',
        
        // 导出
        'export_success' => '导出成功！',
    ]
];
?>