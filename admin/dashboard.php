<?php
// Bootstrap the application
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

// Ensure user is authenticated
ensureUserAuthenticated();

// Get current user information
$user = getCurrentUser();

// Get dashboard stats
$pdo = getPDO();
$stats = [
    'services' => countTable($pdo, 'services'),
    'pages' => countTable($pdo, 'pages'),
    'contacts' => countTable($pdo, 'contact_submissions'),
    'visitors' => getVisitorsCount($pdo)
];

// Get recent contact submissions
$recentContacts = getRecentContactSubmissions($pdo, 5);

// Set active nav item based on URL parameter
$active = $_GET['section'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Zetarise</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js for analytics graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        .chart-container {
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .chart-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--text);
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-hover);
        }
        
        .chart-canvas {
            height: 300px;
            width: 100%;
        }
        
        /* Recent Contacts */
        .recent-list {
            list-style: none;
        }
        
        .recent-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border);
        }
        
        .recent-item:last-child {
            border-bottom: none;
        }
        
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .contact-name {
            font-weight: 500;
        }
        
        .contact-meta {
            font-size: 0.8rem;
            color: var(--text-muted);
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

        /* Color indicators */
        .color-primary { color: var(--primary); }
        .color-success { color: var(--success); }
        .color-warning { color: var(--warning); }
        .color-info { color: var(--info); }
        
        .bg-primary { background-color: var(--primary); }
        .bg-success { background-color: var(--success); }
        .bg-warning { background-color: var(--warning); }
        .bg-info { background-color: var(--info); }
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
            <a href="?section=dashboard" class="nav-link <?= $active === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="nav-heading">Content</div>
            <a href="?section=services" class="nav-link <?= $active === 'services' ? 'active' : '' ?>">
                <i class="fas fa-cogs"></i>
                <span>Services</span>
            </a>
            <a href="?section=pages" class="nav-link <?= $active === 'pages' ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i>
                <span>Pages</span>
            </a>
            
            <div class="nav-heading">Marketing</div>
            <a href="manage_seo.php" class="nav-link <?= $active === 'seo' ? 'active' : '' ?>">
                <i class="fas fa-search"></i>
                <span>SEO Management</span>
            </a>
            <a href="?section=contact" class="nav-link <?= $active === 'contact' ? 'active' : '' ?>">
                <i class="fas fa-address-book"></i>
                <span>Contact Details</span>
            </a>
            
            <div class="nav-heading">Communications</div>
            <a href="?section=messages" class="nav-link <?= $active === 'messages' ? 'active' : '' ?>">
                <i class="fas fa-envelope"></i>
                <span>Contact Submissions</span>
            </a>
            
            <div class="nav-heading">System</div>
            <a href="?section=settings" class="nav-link <?= $active === 'settings' ? 'active' : '' ?>">
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
        <?php if($active === 'dashboard'): ?>
            <h1 class="page-title">Dashboard Overview</h1>
            
            <!-- Stats Cards -->
            <div class="cards-grid">
                <div class="card stat-card">
                    <i class="fas fa-cogs icon color-primary"></i>
                    <div class="label">Services</div>
                    <div class="value"><?= $stats['services'] ?></div>
                    <div class="change">
                        <i class="fas fa-arrow-up"></i> 8% from last month
                    </div>
                </div>
                
                <div class="card stat-card">
                    <i class="fas fa-file-alt icon color-info"></i>
                    <div class="label">Pages</div>
                    <div class="value"><?= $stats['pages'] ?></div>
                    <div class="change">
                        <i class="fas fa-arrow-up"></i> 5% from last month
                    </div>
                </div>
                
                <div class="card stat-card">
                    <i class="fas fa-envelope icon color-warning"></i>
                    <div class="label">Contact Forms</div>
                    <div class="value"><?= $stats['contacts'] ?></div>
                    <div class="change">
                        <i class="fas fa-arrow-up"></i> 12% from last month
                    </div>
                </div>
                
                <div class="card stat-card">
                    <i class="fas fa-users icon color-success"></i>
                    <div class="label">Visitors</div>
                    <div class="value"><?= $stats['visitors'] ?></div>
                    <div class="change">
                        <i class="fas fa-arrow-up"></i> 18% from last month
                    </div>
                </div>
            </div>
            
            <!-- Charts -->
            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">Website Traffic Overview</div>
                    <div class="chart-actions">
                        <button class="btn" data-period="week">Week</button>
                        <button class="btn" data-period="month">Month</button>
                        <button class="btn" data-period="year">Year</button>
                    </div>
                </div>
                <canvas id="trafficChart" class="chart-canvas"></canvas>
            </div>
            
            <div class="cards-grid">
                <!-- Recent Contact Submissions -->
                <div class="card" style="grid-column: span 2;">
                    <h3 style="margin-bottom: 1rem;">Recent Contact Submissions</h3>
                    <?php if (empty($recentContacts)): ?>
                        <p>No recent submissions.</p>
                    <?php else: ?>
                        <ul class="recent-list">
                            <?php foreach($recentContacts as $contact): ?>
                            <li class="recent-item">
                                <div class="contact-info">
                                    <div class="contact-name"><?= e($contact['name']) ?></div>
                                    <div class="contact-meta">
                                        <span><?= e($contact['email']) ?></span> • 
                                        <span><?= e($contact['submitted_at']) ?></span>
                                    </div>
                                </div>
                                <div>
                                    <a href="?section=messages&id=<?= $contact['id'] ?>" class="btn">View</a>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                
                <!-- Referral Traffic -->
                <div class="card">
                    <h3 style="margin-bottom: 1rem;">Referral Sources</h3>
                    <canvas id="referralChart" height="220"></canvas>
                </div>
            </div>
        
        <?php elseif($active === 'services'): ?>
            <!-- Services Content -->
            <h1 class="page-title">Services Management</h1>
            <p>Manage your website's services section here.</p>
            <!-- Service management interface will be implemented here -->
            
        <?php elseif($active === 'pages'): ?>
            <!-- Pages Content -->
            <h1 class="page-title">Pages Management</h1>
            <p>Manage your website's pages here.</p>
            <!-- Pages management interface will be implemented here -->
            
        <?php elseif($active === 'seo'): ?>
            <!-- SEO Content -->
            <h1 class="page-title">SEO Management</h1>
            <p>Optimize your website's search engine visibility.</p>
            <div style="margin-top: 1.5rem;">
                <a href="manage_seo.php" class="btn btn-primary">
                    <i class="fas fa-cog"></i> Manage SEO Settings
                </a>
            </div>
            
        <?php elseif($active === 'contact'): ?>
            <!-- Contact Details Content -->
            <h1 class="page-title">Contact Details</h1>
            <p>Update your business contact information.</p>
            <!-- Contact details management interface will be implemented here -->
            
        <?php elseif($active === 'messages'): ?>
            <!-- Messages Content -->
            <h1 class="page-title">Contact Submissions</h1>
            <p>View and manage customer inquiries.</p>
            <!-- Contact form submissions interface will be implemented here -->
            
        <?php elseif($active === 'settings'): ?>
            <!-- Settings Content -->
            <h1 class="page-title">System Settings</h1>
            <p>Configure your website settings.</p>
            <!-- Settings interface will be implemented here -->
            
        <?php endif; ?>
    </main>

    <script>
        // Traffic Chart
        const trafficCtx = document.getElementById('trafficChart').getContext('2d');
        const trafficChart = new Chart(trafficCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Visitors',
                    data: [1200, 1900, 3000, 3500, 2500, 4000, 4800, 5200, 6100, 5900, 6500, 7500],
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124, 58, 237, 0.1)',
                    tension: 0.3,
                    fill: true
                }, {
                    label: 'Page Views',
                    data: [3000, 4500, 6000, 7500, 6000, 8000, 9500, 10500, 12000, 11500, 13000, 15000],
                    borderColor: '#06b6d4',
                    backgroundColor: 'rgba(6, 182, 212, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#e6eef8'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });

        // Referral Chart
        const referralCtx = document.getElementById('referralChart').getContext('2d');
        const referralChart = new Chart(referralCtx, {
            type: 'doughnut',
            data: {
                labels: ['Google', 'Direct', 'Social', 'Referral'],
                datasets: [{
                    data: [45, 25, 20, 10],
                    backgroundColor: ['#7c3aed', '#06b6d4', '#10b981', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#e6eef8',
                            padding: 20
                        }
                    }
                },
                cutout: '70%'
            }
        });

        // Mobile menu toggle
        document.querySelector('.mobile-toggle')?.addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Period buttons for traffic chart
        document.querySelectorAll('[data-period]').forEach(button => {
            button.addEventListener('click', () => {
                // Would typically load new data via AJAX here
                alert(`Loading ${button.dataset.period} data...`);
            });
        });
    </script>
</body>
</html>
