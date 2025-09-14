<?php
// Bootstrap the application
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

// Ensure user is authenticated
ensureUserAuthenticated();

// Get current user information
$user = getCurrentUser();

// Set active nav item
$active = 'seo';

// Initialize PDO connection
$pdo = getPDO();

// Process form submission
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_seo') {
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        $entity_type = $_POST['entity_type'];
        $entity_id = (int)$_POST['entity_id'];
        
        // Check if SEO record exists
        $checkStmt = $pdo->prepare("SELECT id FROM seo WHERE entity_type = :type AND entity_id = :id");
        $checkStmt->execute([':type' => $entity_type, ':id' => $entity_id]);
        $seoExists = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        $seoData = [
            ':entity_type' => $entity_type,
            ':entity_id' => $entity_id,
            ':meta_title' => $_POST['meta_title'] ?? '',
            ':meta_description' => $_POST['meta_description'] ?? '',
            ':meta_keywords' => $_POST['meta_keywords'] ?? '',
            ':og_title' => $_POST['og_title'] ?? '',
            ':og_description' => $_POST['og_description'] ?? '',
            ':og_image' => $_POST['og_image'] ?? '',
            ':canonical_url' => $_POST['canonical_url'] ?? '',
            ':is_indexable' => isset($_POST['is_indexable']) ? 1 : 0,
            ':schema_markup' => $_POST['schema_markup'] ?? ''
        ];
        
        if ($seoExists) {
            // Update existing record
            $sql = "UPDATE seo SET 
                meta_title = :meta_title,
                meta_description = :meta_description,
                meta_keywords = :meta_keywords,
                og_title = :og_title,
                og_description = :og_description,
                og_image = :og_image,
                canonical_url = :canonical_url,
                is_indexable = :is_indexable,
                schema_markup = :schema_markup,
                updated_at = NOW()
                WHERE entity_type = :entity_type AND entity_id = :entity_id";
        } else {
            // Insert new record
            $sql = "INSERT INTO seo (
                entity_type, entity_id, meta_title, meta_description, meta_keywords,
                og_title, og_description, og_image, canonical_url, is_indexable, schema_markup, updated_at
                ) VALUES (
                :entity_type, :entity_id, :meta_title, :meta_description, :meta_keywords,
                :og_title, :og_description, :og_image, :canonical_url, :is_indexable, :schema_markup, NOW()
                )";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($seoData);
        
        // Commit transaction
        $pdo->commit();
        
        $message = "SEO settings saved successfully!";
        $messageType = "success";
    } catch (PDOException $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        $message = "Error: " . $e->getMessage();
        $messageType = "error";
    }
}

// Get entity type and id from query parameters
$entityType = $_GET['type'] ?? '';
$entityId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Function to get entity list for dropdown
function getEntityList($pdo, $type) {
    if ($type === 'page') {
        $sql = "SELECT id, title, slug FROM pages ORDER BY title";
    } elseif ($type === 'service') {
        $sql = "SELECT id, title, slug FROM services ORDER BY title";
    } else {
        return [];
    }
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get SEO data for a specific entity
function getSeoData($pdo, $type, $id) {
    $stmt = $pdo->prepare("
        SELECT * FROM seo 
        WHERE entity_type = :type AND entity_id = :id
    ");
    $stmt->execute([':type' => $type, ':id' => $id]);
    $seoData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$seoData) {
        // Return empty default structure
        return [
            'entity_type' => $type,
            'entity_id' => $id,
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'og_title' => '',
            'og_description' => '',
            'og_image' => '',
            'canonical_url' => '',
            'is_indexable' => 1,
            'schema_markup' => ''
        ];
    }
    
    return $seoData;
}

// Get entity details
function getEntityDetails($pdo, $type, $id) {
    if (!$id) return null;
    
    if ($type === 'page') {
        $table = 'pages';
    } elseif ($type === 'service') {
        $table = 'services';
    } else {
        return null;
    }
    
    $stmt = $pdo->prepare("SELECT id, title, slug FROM {$table} WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get all entities with SEO status
function getAllEntitiesWithSeoStatus($pdo) {
    // Get pages with SEO status
    $pagesStmt = $pdo->query("
        SELECT p.id, p.title, p.slug, 'page' as entity_type,
               CASE WHEN s.id IS NOT NULL THEN 1 ELSE 0 END as has_seo,
               s.meta_title, s.meta_description, s.is_indexable
        FROM pages p
        LEFT JOIN seo s ON s.entity_type = 'page' AND s.entity_id = p.id
        ORDER BY p.title
    ");
    $pages = $pagesStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get services with SEO status
    $servicesStmt = $pdo->query("
        SELECT s.id, s.title, s.slug, 'service' as entity_type,
               CASE WHEN seo.id IS NOT NULL THEN 1 ELSE 0 END as has_seo,
               seo.meta_title, seo.meta_description, seo.is_indexable
        FROM services s
        LEFT JOIN seo seo ON seo.entity_type = 'service' AND seo.entity_id = s.id
        ORDER BY s.title
    ");
    $services = $servicesStmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'pages' => $pages,
        'services' => $services
    ];
}

// Load data based on the current view
$seoData = null;
$entityDetails = null;
$entities = null;

if ($entityType && $entityId) {
    // Editing a specific entity
    $seoData = getSeoData($pdo, $entityType, $entityId);
    $entityDetails = getEntityDetails($pdo, $entityType, $entityId);
} else {
    // Overview of all entities
    $entities = getAllEntitiesWithSeoStatus($pdo);
}

// Function to safely output text in HTML contex

// Helper function to check if a string is empty
function isEmpty($value) {
    return trim($value) === '';
}

// Get count of entities with SEO data
function getSeoStats($entities) {
    $stats = [
        'pages_total' => count($entities['pages']),
        'pages_with_seo' => 0,
        'services_total' => count($entities['services']),
        'services_with_seo' => 0
    ];
    
    foreach ($entities['pages'] as $page) {
        if ($page['has_seo']) {
            $stats['pages_with_seo']++;
        }
    }
    
    foreach ($entities['services'] as $service) {
        if ($service['has_seo']) {
            $stats['services_with_seo']++;
        }
    }
    
    return $stats;
}

// Calculate SEO stats if we have entities data
$seoStats = $entities ? getSeoStats($entities) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEO Management — Zetarise Admin</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #7c3aed;
            --primary-hover: #6d28d9;
            --secondary: #06b6d4;
            --dark: #0f1724;
            --dark-light: #1e293b;
            --gray-light: #f1f5f9;
            --text: #e6eef8;
            --text-muted: #94a3b8;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #3b82f6;
            --border: rgba(255,255,255,0.06);
            --card-bg: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.02));
            --panel-shadow: 0 10px 30px rgba(2,6,23,0.7);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(ellipse at 10% 20%, rgba(124,58,237,0.08), transparent 80%),
                      radial-gradient(ellipse at 90% 80%, rgba(6,182,212,0.06), transparent 80%),
                      var(--dark);
            color: var(--text);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 280px 1fr;
            grid-template-rows: auto 1fr;
            grid-template-areas:
                "sidebar header"
                "sidebar main";
        }
        
        /* Header */
        .header {
            grid-area: header;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
            background: rgba(15,23,36,0.7);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        /* Sidebar */
        .sidebar {
            grid-area: sidebar;
            background: rgba(15,23,36,0.9);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--border);
            padding: 2rem 0;
            display: flex;
            flex-direction: column;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 1.5rem;
            margin-bottom: 2.5rem;
        }
        
        .logo {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
        
        .brand-name {
            font-size: 20px;
            font-weight: 600;
        }
        
        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding: 0 1rem;
        }
        
        .nav-heading {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 0.75rem 0.5rem;
            margin-top: 1rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            color: var(--text);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.05);
        }
        
        .nav-link.active {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 5px 15px rgba(124,58,237,0.3);
        }
        
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        
        /* Main Content */
        .main-content {
            grid-area: main;
            padding: 2rem;
            overflow: auto;
        }
        
        .page-title {
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
            font-weight: 600;
        }
        
        /* Cards Grid */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .card {
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .stat-card {
            position: relative;
            overflow: hidden;
        }
        
        .stat-card .icon {
            position: absolute;
            right: 1rem;
            top: 1rem;
            font-size: 1.5rem;
            opacity: 0.15;
        }
        
        .stat-card .label {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }
        
        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-card .change {
            font-size: 0.8rem;
            color: var(--success);
        }
        
        /* Buttons */
        .btn {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .btn-sm {
            padding: 0.35rem 0.7rem;
            font-size: 0.75rem;
        }
        
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-hover);
        }
        
        .btn-success {
            background: var(--success);
            border-color: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #0da66d;
        }
        
        .btn-danger {
            background: var(--danger);
            border-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        .btn-info {
            background: var(--info);
            border-color: var(--info);
            color: white;
        }
        
        .btn-info:hover {
            background-color: #2563eb;
        }
        
        /* Tables */
        .table-container {
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        
        .table th {
            background: rgba(0,0,0,0.2);
            font-weight: 500;
            color: var(--text-muted);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .table tbody tr:hover {
            background: rgba(255,255,255,0.02);
        }
        
        /* Form Elements */
        .form-container {
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .form-hint {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(0,0,0,0.2);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.2);
        }
        
        .form-control::placeholder {
            color: var(--text-muted);
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .form-check-input {
            width: 1rem;
            height: 1rem;
        }
        
        .form-check-label {
            font-size: 0.9rem;
        }
        
        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-success {
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.2);
            color: #34d399;
        }
        
        .alert-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            color: #f87171;
        }
        
        /* Badges */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-success {
            background: rgba(16,185,129,0.1);
            color: #34d399;
        }
        
        .badge-warning {
            background: rgba(245,158,11,0.1);
            color: #fbbf24;
        }
        
        .badge-danger {
            background: rgba(239,68,68,0.1);
            color: #f87171;
        }
        
        .badge-info {
            background: rgba(59,130,246,0.1);
            color: #60a5fa;
        }
        
        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }
        
        .tab {
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            font-weight: 500;
            color: var(--text-muted);
        }
        
        .tab:hover {
            color: var(--text);
        }
        
        .tab.active {
            border-bottom-color: var(--primary);
            color: var(--text);
        }
        
        /* SEO Specific Styles */
        .seo-preview {
            background: rgba(0,0,0,0.3);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .seo-preview-title {
            font-size: 1.25rem;
            color: #3b82f6;
            margin-bottom: 0.5rem;
            text-decoration: underline;
        }
        
        .seo-preview-url {
            color: var(--success);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .seo-preview-description {
            font-size: 0.9rem;
            line-height: 1.5;
            color: var(--text-muted);
        }
        
        .entity-stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .entity-stats .stat {
            background: rgba(0,0,0,0.2);
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .entity-stats .stat .value {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .entity-stats .stat .label {
            font-size: 0.875rem;
            color: var(--text-muted);
        }
        
        .seo-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .seo-status-indicator {
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
        }
        
        .seo-status-good {
            background-color: var(--success);
        }
        
        .seo-status-missing {
            background-color: var(--warning);
        }
        
        .seo-status-attention {
            background-color: var(--danger);
        }
        
        .content-toggle {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        /* Character counter */
        .char-counter {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-align: right;
            margin-top: 0.25rem;
        }
        
        .char-counter.warning {
            color: var(--warning);
        }
        
        .char-counter.danger {
            color: var(--danger);
        }

        /* Responsive */
        @media (max-width: 992px) {
            body {
                grid-template-columns: 1fr;
                grid-template-areas:
                    "header"
                    "main";
            }
            
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                width: 280px;
                z-index: 1000;
                transition: all 0.3s;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .mobile-toggle {
                display: block;
            }
        }
        
        @media (max-width: 768px) {
            .cards-grid {
                grid-template-columns: 1fr;
            }
            
            .entity-stats {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .entity-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo-container">
            <div class="logo">ZR</div>
            <div class="brand-name">Zetarise</div>
        </div>
        
        <nav class="nav-menu">
            <a href="dashboard.php" class="nav-link">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="nav-heading">Content</div>
            <a href="dashboard.php?section=services" class="nav-link">
                <i class="fas fa-cogs"></i>
                <span>Services</span>
            </a>
            <a href="dashboard.php?section=pages" class="nav-link">
                <i class="fas fa-file-alt"></i>
                <span>Pages</span>
            </a>
            
            <div class="nav-heading">Marketing</div>
            <a href="manage_seo.php" class="nav-link active">
                <i class="fas fa-search"></i>
                <span>SEO Management</span>
            </a>
            <a href="dashboard.php?section=contact" class="nav-link">
                <i class="fas fa-address-book"></i>
                <span>Contact Details</span>
            </a>
            
            <div class="nav-heading">Communications</div>
            <a href="dashboard.php?section=messages" class="nav-link">
                <i class="fas fa-envelope"></i>
                <span>Contact Submissions</span>
            </a>
            
            <div class="nav-heading">System</div>
            <a href="dashboard.php?section=settings" class="nav-link">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <div>Zetarise Admin Portal v1.0</div>
        </div>
    </aside>
    
    <!-- Header -->
    <header class="header">
        <div>
            <button class="btn mobile-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <div class="user-profile">
            <div class="avatar"><?= strtoupper(substr($user['username'] ?? 'A', 0, 1)) ?></div>
            <div>
                <div><?= e($user['username'] ?? 'Admin') ?></div>
                <a href="logout.php" style="font-size: 0.8rem; color: var(--text-muted);">Sign Out</a>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="main-content">
        <?php if ($entityType && $entityId && $entityDetails): ?>
            <!-- SEO Edit Mode -->
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="page-title">
                    <a href="manage_seo.php" class="btn btn-sm" style="margin-right: 1rem;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    SEO Settings: <?= e($entityDetails['title']) ?>
                </h1>
            </div>
            
            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'error' ?>">
                    <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                    <?= e($message) ?>
                </div>
            <?php endif; ?>
            
            <div class="tabs">
                <div class="tab active" data-tab="basic">Basic SEO</div>
                <div class="tab" data-tab="social">Social Media</div>
                <div class="tab" data-tab="advanced">Advanced</div>
                <div class="tab" data-tab="preview">Preview</div>
            </div>
            
            <form method="post" action="manage_seo.php">
                <input type="hidden" name="action" value="save_seo">
                <input type="hidden" name="entity_type" value="<?= e($entityType) ?>">
                <input type="hidden" name="entity_id" value="<?= e($entityId) ?>">
                
                <!-- Basic SEO Tab -->
                <div class="tab-content" id="basic-tab" style="display: block;">
                    <div class="form-container">
                        <div class="form-group">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" id="meta_title" name="meta_title" class="form-control char-count" 
                                   value="<?= e($seoData['meta_title']) ?>" 
                                   placeholder="Enter page title (50-60 characters)" 
                                   maxlength="70" 
                                   data-optimal-min="50" 
                                   data-optimal-max="60">
                            <div class="char-counter" id="meta_title_counter">0/60 characters</div>
                            <small class="form-hint">The title that appears in search engine results. Aim for 50-60 characters.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea id="meta_description" name="meta_description" class="form-control char-count" 
                                      placeholder="Enter description (150-160 characters)" 
                                      maxlength="160" 
                                      data-optimal-min="150" 
                                      data-optimal-max="160"><?= e($seoData['meta_description']) ?></textarea>
                            <div class="char-counter" id="meta_description_counter">0/160 characters</div>
                            <small class="form-hint">A brief description that appears below the title in search results. Aim for 150-160 characters.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text" id="meta_keywords" name="meta_keywords" class="form-control" 
                                   value="<?= e($seoData['meta_keywords']) ?>" 
                                   placeholder="keyword1, keyword2, keyword3">
                            <small class="form-hint">Comma-separated keywords (less important for modern SEO but still useful for organization).</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="canonical_url" class="form-label">Canonical URL</label>
                            <input type="text" id="canonical_url" name="canonical_url" class="form-control" 
                                   value="<?= e($seoData['canonical_url']) ?>" 
                                   placeholder="https://yourdomain.com/page-path">
                            <small class="form-hint">The preferred URL for this content (helps prevent duplicate content issues).</small>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" id="is_indexable" name="is_indexable" class="form-check-input" 
                                       <?= $seoData['is_indexable'] ? 'checked' : '' ?>>
                                <label for="is_indexable" class="form-check-label">Allow search engines to index this page</label>
                            </div>
                            <small class="form-hint">If unchecked, a "noindex" meta tag will be added to prevent search engines from indexing this page.</small>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media Tab -->
                <div class="tab-content" id="social-tab" style="display: none;">
                    <div class="form-container">
                        <div class="form-group">
                            <label for="og_title" class="form-label">Open Graph Title</label>
                            <input type="text" id="og_title" name="og_title" class="form-control char-count" 
                                   value="<?= e($seoData['og_title']) ?>" 
                                   placeholder="Enter social media title"
                                   maxlength="95"
                                   data-optimal-max="95">
                            <div class="char-counter" id="og_title_counter">0/95 characters</div>
                            <small class="form-hint">The title that appears when shared on social media. Leave blank to use Meta Title.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="og_description" class="form-label">Open Graph Description</label>
                            <textarea id="og_description" name="og_description" class="form-control char-count" 
                                      placeholder="Enter social media description"
                                      maxlength="200"
                                      data-optimal-max="200"><?= e($seoData['og_description']) ?></textarea>
                            <div class="char-counter" id="og_description_counter">0/200 characters</div>
                            <small class="form-hint">The description that appears when shared on social media. Leave blank to use Meta Description.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="og_image" class="form-label">Open Graph Image URL</label>
                            <input type="text" id="og_image" name="og_image" class="form-control" 
                                   value="<?= e($seoData['og_image']) ?>" 
                                   placeholder="https://yourdomain.com/images/og-image.jpg">
                            <small class="form-hint">The image that appears when shared on social media. Recommended size: 1200×630 pixels.</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="button" class="btn btn-info" id="fill-from-basic">
                                <i class="fas fa-sync"></i> Fill from Basic SEO
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Advanced Tab -->
                <div class="tab-content" id="advanced-tab" style="display: none;">
                    <div class="form-container">
                        <div class="form-group">
                            <label for="schema_markup" class="form-label">JSON-LD Schema Markup</label>
                            <textarea id="schema_markup" name="schema_markup" class="form-control" 
                                      placeholder='{"@context": "https://schema.org", "@type": "WebPage", ...}' 
                                      style="min-height: 200px; font-family: monospace;"><?= e($seoData['schema_markup']) ?></textarea>
                            <small class="form-hint">Add structured data in JSON-LD format to enhance your search results.</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="button" class="btn btn-info" id="generate-schema">
                                <i class="fas fa-magic"></i> Generate Basic Schema
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Preview Tab -->
                <div class="tab-content" id="preview-tab" style="display: none;">
                    <div class="form-container">
                        <h3 style="margin-bottom: 1rem;">Search Engine Result Preview</h3>
                        
                        <div class="seo-preview">
                            <div class="seo-preview-title" id="preview-title">
                                <?= e($seoData['meta_title']) ?: 'Page Title' ?>
                            </div>
                            <div class="seo-preview-url" id="preview-url">
                                <?= e($seoData['canonical_url'] ?: 'https://zetarise.com/' . $entityType . 's/' . $entityDetails['slug']) ?>
                            </div>
                            <div class="seo-preview-description" id="preview-description">
                                <?= e($seoData['meta_description']) ?: 'This is where your meta description will appear in search results. Make it compelling to increase click-through rates.' ?>
                            </div>
                        </div>
                        
                        <h3 style="margin-bottom: 1rem;">Social Media Preview</h3>
                        
                        <div class="seo-preview" style="background: #1e293b;">
                            <?php if(!empty($seoData['og_image'])): ?>
                                <div style="margin-bottom: 1rem; max-height: 200px; overflow: hidden; border-radius: 8px;">
                                    <img src="<?= e($seoData['og_image']) ?>" alt="OG Image Preview" style="width: 100%; height: auto; object-fit: cover;">
                                </div>
                            <?php else: ?>
                                <div style="margin-bottom: 1rem; height: 150px; background: #2d3748; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                                    <i class="fas fa-image" style="font-size: 2rem;"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem;">zetarise.com</div>
                            <div style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem;" id="preview-og-title">
                                <?= e($seoData['og_title'] ?: $seoData['meta_title']) ?: 'Social Media Title' ?>
                            </div>
                            <div style="font-size: 0.9rem; color: var(--text-muted);" id="preview-og-description">
                                <?= e($seoData['og_description'] ?: $seoData['meta_description']) ?: 'This is where your social media description will appear when your content is shared.' ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="manage_seo.php" class="btn">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save SEO Settings
                    </button>
                </div>
            </form>
            
        <?php else: ?>
            <!-- SEO Overview Mode -->
            <h1 class="page-title">SEO Management</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'error' ?>">
                    <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                    <?= e($message) ?>
                </div>
            <?php endif; ?>
            
            <div class="entity-stats">
                <div class="stat">
                    <div class="value"><?= $seoStats['pages_with_seo'] ?>/<?= $seoStats['pages_total'] ?></div>
                    <div class="label">Pages with SEO</div>
                </div>
                <div class="stat">
                    <div class="value"><?= $seoStats['services_with_seo'] ?>/<?= $seoStats['services_total'] ?></div>
                    <div class="label">Services with SEO</div>
                </div>
                <div class="stat">
                    <div class="value"><?= round(($seoStats['pages_with_seo'] + $seoStats['services_with_seo']) / ($seoStats['pages_total'] + $seoStats['services_total']) * 100) ?>%</div>
                    <div class="label">Overall Completion</div>
                </div>
            </div>
            
            <div class="content-toggle">
                <button class="btn btn-sm entity-filter active" data-filter="all">
                    <i class="fas fa-globe"></i> All
                </button>
                <button class="btn btn-sm entity-filter" data-filter="page">
                    <i class="fas fa-file-alt"></i> Pages
                </button>
                <button class="btn btn-sm entity-filter" data-filter="service">
                    <i class="fas fa-cogs"></i> Services
                </button>
            </div>
            
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>URL</th>
                            <th>SEO Status</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entities['pages'] as $page): ?>
                            <tr class="entity-row" data-type="page">
                                <td><?= e($page['title']) ?></td>
                                <td><span class="badge badge-info">Page</span></td>
                                <td>/<?= e($page['slug']) ?></td>
                                <td>
                                    <?php if ($page['has_seo']): ?>
                                        <?php if (isEmpty($page['meta_title']) || isEmpty($page['meta_description'])): ?>
                                            <div class="seo-status">
                                                <div class="seo-status-indicator seo-status-attention"></div>
                                                <span>Needs Attention</span>
                                            </div>
                                        <?php else: ?>
                                            <div class="seo-status">
                                                <div class="seo-status-indicator seo-status-good"></div>
                                                <span>Optimized</span>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="seo-status">
                                            <div class="seo-status-indicator seo-status-missing"></div>
                                            <span>Not Set</span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="manage_seo.php?type=page&id=<?= $page['id'] ?>" class="btn btn-sm btn-primary">
                                        <?php if ($page['has_seo']): ?>
                                            <i class="fas fa-edit"></i> Edit
                                        <?php else: ?>
                                            <i class="fas fa-plus"></i> Add
                                        <?php endif; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php foreach ($entities['services'] as $service): ?>
                            <tr class="entity-row" data-type="service">
                                <td><?= e($service['title']) ?></td>
                                <td><span class="badge badge-success">Service</span></td>
                                <td>/services/<?= e($service['slug']) ?></td>
                                <td>
                                    <?php if ($service['has_seo']): ?>
                                        <?php if (isEmpty($service['meta_title']) || isEmpty($service['meta_description'])): ?>
                                            <div class="seo-status">
                                                <div class="seo-status-indicator seo-status-attention"></div>
                                                <span>Needs Attention</span>
                                            </div>
                                        <?php else: ?>
                                            <div class="seo-status">
                                                <div class="seo-status-indicator seo-status-good"></div>
                                                <span>Optimized</span>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="seo-status">
                                            <div class="seo-status-indicator seo-status-missing"></div>
                                            <span>Not Set</span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="manage_seo.php?type=service&id=<?= $service['id'] ?>" class="btn btn-sm btn-primary">
                                        <?php if ($service['has_seo']): ?>
                                            <i class="fas fa-edit"></i> Edit
                                        <?php else: ?>
                                            <i class="fas fa-plus"></i> Add
                                        <?php endif; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div style="background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border); padding: 1.5rem; margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1rem;">SEO Best Practices</h3>
                <ul style="padding-left: 1.5rem; margin-bottom: 1rem;">
                    <li><strong>Meta Titles:</strong> Keep between 50-60 characters. Include primary keyword.</li>
                    <li><strong>Meta Descriptions:</strong> Keep between 150-160 characters. Include call-to-action.</li>
                    <li><strong>URL Structure:</strong> Use descriptive, keyword-rich URLs. Avoid parameters.</li>
                    <li><strong>Open Graph Tags:</strong> Optimize for social sharing with unique titles, descriptions and images.</li>
                    <li><strong>Schema Markup:</strong> Implement structured data to enhance search visibility.</li>
                </ul>
                <p>Regularly review and update your SEO settings to maintain optimal search visibility.</p>
            </div>
        <?php endif; ?>
    </main>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-toggle')?.addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('active');
        });
        
        // Tab functionality
        const tabs = document.querySelectorAll('.tab');
        if (tabs.length) {
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Deactivate all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    // Activate clicked tab
                    tab.classList.add('active');
                    
                    // Hide all tab content
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.style.display = 'none';
                    });
                    
                    // Show corresponding tab content
                    const tabContent = document.getElementById(tab.dataset.tab + '-tab');
                    if (tabContent) {
                        tabContent.style.display = 'block';
                    }
                });
            });
        }
        
        // Character counter functionality
        document.querySelectorAll('.char-count').forEach(input => {
            const counter = document.getElementById(input.id + '_counter');
            if (counter) {
                const updateCount = () => {
                    const max = input.dataset.optimalMax || input.maxLength;
                    const min = input.dataset.optimalMin || 0;
                    const count = input.value.length;
                    counter.textContent = `${count}/${max} characters`;
                    
                    // Reset classes
                    counter.className = 'char-counter';
                    
                    // Add warning or danger class based on count
                    if (count > 0 && count < min) {
                        counter.classList.add('warning');
                    } else if (count > parseInt(max) * 0.9) {
                        counter.classList.add('danger');
                    }
                };
                
                updateCount(); // Initial count
                input.addEventListener('input', updateCount);
            }
        });
        
        // Entity filter functionality
        const entityFilters = document.querySelectorAll('.entity-filter');
        if (entityFilters.length) {
            entityFilters.forEach(filter => {
                filter.addEventListener('click', () => {
                    // Update active filter
                    entityFilters.forEach(f => f.classList.remove('active'));
                    filter.classList.add('active');
                    
                    const filterType = filter.dataset.filter;
                    const rows = document.querySelectorAll('.entity-row');
                    
                    rows.forEach(row => {
                        if (filterType === 'all' || row.dataset.type === filterType) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });
        }
        
        // Fill social fields from basic SEO
        const fillFromBasicBtn = document.getElementById('fill-from-basic');
        if (fillFromBasicBtn) {
            fillFromBasicBtn.addEventListener('click', () => {
                const metaTitle = document.getElementById('meta_title');
                const metaDescription = document.getElementById('meta_description');
                const ogTitle = document.getElementById('og_title');
                const ogDescription = document.getElementById('og_description');
                
                if (metaTitle && ogTitle) {
                    ogTitle.value = metaTitle.value;
                    // Trigger input event to update character count
                    ogTitle.dispatchEvent(new Event('input'));
                }
                
                if (metaDescription && ogDescription) {
                    ogDescription.value = metaDescription.value;
                    // Trigger input event to update character count
                    ogDescription.dispatchEvent(new Event('input'));
                }
            });
        }
        
        // Generate basic schema markup
        const generateSchemaBtn = document.getElementById('generate-schema');
        const schemaMarkup = document.getElementById('schema_markup');
        if (generateSchemaBtn && schemaMarkup) {
            generateSchemaBtn.addEventListener('click', () => {
                const metaTitle = document.getElementById('meta_title').value;
                const metaDescription = document.getElementById('meta_description').value;
                const canonicalUrl = document.getElementById('canonical_url').value;
                const ogImage = document.getElementById('og_image').value;
                
                // Get entity type from hidden input
                const entityType = document.querySelector('input[name="entity_type"]').value;
                
                // Generate schema based on entity type
                let schemaType = 'WebPage';
                if (entityType === 'service') {
                    schemaType = 'Service';
                }
                
                // Create basic schema template
                const schema = {
                    "@context": "https://schema.org",
                    "@type": schemaType,
                    "name": metaTitle,
                    "description": metaDescription,
                    "url": canonicalUrl
                };
                
                if (ogImage) {
                    schema.image = ogImage;
                }
                
                // Add organization data
                schema.provider = {
                    "@type": "Organization",
                    "name": "Zetarise",
                    "url": "https://zetarise.com"
                };
                
                schemaMarkup.value = JSON.stringify(schema, null, 2);
            });
        }
        
        // Live preview updates
        document.querySelectorAll('#meta_title, #meta_description, #canonical_url').forEach(input => {
            input.addEventListener('input', () => {
                if (input.id === 'meta_title') {
                    document.getElementById('preview-title').textContent = input.value || 'Page Title';
                }
                
                if (input.id === 'meta_description') {
                    document.getElementById('preview-description').textContent = input.value || 'This is where your meta description will appear in search results. Make it compelling to increase click-through rates.';
                }
                
                if (input.id === 'canonical_url') {
                    document.getElementById('preview-url').textContent = input.value || 'https://zetarise.com/page';
                }
            });
        });
        
        document.querySelectorAll('#og_title, #og_description').forEach(input => {
            input.addEventListener('input', () => {
                if (input.id === 'og_title') {
                    const metaTitle = document.getElementById('meta_title').value;
                    document.getElementById('preview-og-title').textContent = input.value || metaTitle || 'Social Media Title';
                }
                
                if (input.id === 'og_description') {
                    const metaDescription = document.getElementById('meta_description').value;
                    document.getElementById('preview-og-description').textContent = input.value || metaDescription || 'This is where your social media description will appear when your content is shared.';
                }
            });
        });
    </script>
</body>
</html>
