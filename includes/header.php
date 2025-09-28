<?php
// Load database connection and required functions
require_once 'db.php';
$pdo = getPDO();

// Get page info for SEO
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$pageName = $currentPage === 'index' ? 'home' : $currentPage;

// Get SEO data from database
$pageTitle = getSetting($pdo, 'site_name', 'ZetaRise') . ' - ' . getSetting($pdo, 'site_tagline', 'Premier IT Solutions & Technology Services');
$pageDescription = 'Leading IT Solutions & Technology Services Company. Web Development, Cloud Solutions, Digital Transformation.';
$pageKeywords = 'IT solutions, web development, cloud services, digital transformation, technology consulting';
$ogImage = 'https://zetarise.com/assets/images/og-default.jpg';

// Try to load specific page SEO data if available
try {
    $stmt = $pdo->prepare("SELECT p.id, s.meta_title, s.meta_description, s.meta_keywords, s.og_title, s.og_description, s.og_image 
                           FROM pages p 
                           LEFT JOIN seo s ON s.entity_type = 'page' AND s.entity_id = p.id 
                           WHERE p.slug = ? OR p.slug = 'home' LIMIT 1");
    $stmt->execute([$pageName]);
    $seoData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($seoData) {
        $pageTitle = $seoData['meta_title'] ?: $pageTitle;
        $pageDescription = $seoData['meta_description'] ?: $pageDescription;
        $pageKeywords = $seoData['meta_keywords'] ?: $pageKeywords;
        $ogImage = $seoData['og_image'] ?: $ogImage;
    }
} catch (PDOException $e) {
    // Silently continue with defaults if there's a database issue
}

// Get main navigation links - Updated to use clean URLs
$navItems = [
    ['url' => '/', 'text' => 'Home'],
    ['url' => '/services', 'text' => 'Services'],
    ['url' => '/about', 'text' => 'About'],
    ['url' => '/contact', 'text' => 'Contact']
];

// Track page analytics
try {
    $stmt = $pdo->prepare("INSERT INTO site_analytics (page_url, visitor_ip, user_agent, referrer, visit_date) 
                           VALUES (?, ?, ?, ?, CURRENT_DATE)");
    $stmt->execute([
        $_SERVER['REQUEST_URI'],
        $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
        $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
        $_SERVER['HTTP_REFERER'] ?? 'direct'
    ]);
} catch (PDOException $e) {
    // Silently continue if analytics tracking fails
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="<?php echo e($pageDescription); ?>">
    <meta name="keywords" content="<?php echo e($pageKeywords); ?>">
    <meta name="author" content="ZetaRise Technologies">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo e($pageTitle); ?>">
    <meta property="og:description" content="<?php echo e($pageDescription); ?>">
    <meta property="og:image" content="<?php echo e($ogImage); ?>">
    <meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:type" content="website">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo e($pageTitle); ?>">
    <meta name="twitter:description" content="<?php echo e($pageDescription); ?>">
    <meta name="twitter:image" content="<?php echo e($ogImage); ?>">
    
    <title><?php echo e($pageTitle); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    
    <!-- Preload Critical Resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #3b82f6;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f8fafc;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --navbar-height: 80px;
            --navbar-height-mobile: 70px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            overflow-x: hidden;
            padding-top: var(--navbar-height);
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 0.75rem 0;
            height: var(--navbar-height);
        }
        
        .navbar.scrolled {
            padding: 0.5rem 0;
            height: calc(var(--navbar-height) - 10px);
        }
        
        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary-color) !important;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .navbar-logo {
            height: 40px;
            width: auto;
            margin-right: 8px;
        }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            margin: 0 10px;
            color: var(--text-dark) !important;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 0;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .navbar-nav .nav-link.active {
            color: var(--primary-color) !important;
            font-weight: 600;
        }
        
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background: var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 100%;
            left: 0;
        }
        
        .btn-primary-custom {
            background: var(--gradient-primary);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
            color: white;
        }
        
        .navbar-toggler {
            border: none;
            outline: none !important;
            padding: 4px 8px;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2833, 37, 41, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Mobile specific styles */
        @media (max-width: 991px) {
            body {
                padding-top: var(--navbar-height-mobile);
            }
            
            .navbar {
                height: var(--navbar-height-mobile);
                padding: 1 rem 0;
            }
            
            .navbar.scrolled {
                height: calc(var(--navbar-height-mobile) - 5px);
                padding: 0.4rem 0;
            }
            
            .navbar-brand {
                font-size: 1.5rem;
            }
            
            .navbar-logo {
                height: 35px;
            }
            
            .navbar-nav {
                text-align: center;
                padding: 20px 0;
                background: rgba(255, 255, 255, 0.98);
                margin-top: 10px;
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            }
            
            .navbar-nav .nav-link {
                margin: 10px 0;
                padding: 10px 20px;
                border-radius: 8px;
                transition: all 0.3s ease;
            }
            
            .navbar-nav .nav-link:hover {
                background: rgba(37, 99, 235, 0.1);
                transform: translateX(5px);
            }
            
            .navbar-nav .nav-link::after {
                display: none;
            }
            
            .btn-primary-custom {
                padding: 10px 25px;
                font-size: 0.9rem;
                margin-top: 10px;
            }
            
            .navbar-collapse {
                border-top: 1px solid rgba(0, 0, 0, 0.1);
                margin-top: 15px;
            }
            
            .navbar-collapse.show {
                animation: slideDown 0.3s ease-out;
            }
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Extra small screens */
        @media (max-width: 576px) {
            body {
                padding-top: calc(var(--navbar-height-mobile) - 5px);
            }
            
            .navbar {
                height: calc(var(--navbar-height-mobile) - 5px);
                padding: 0.4rem 0;
            }
            
            .navbar-brand {
                font-size: 1.3rem;
            }
            
            .navbar-logo {
                height: 30px;
            }
            
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
        }
        
        /* Ensure main content sections have proper spacing */
        #main-content > section:first-child,
        .hero-section,
        .privacy-policy-section,
        .terms-service-section,
        .about-hero-section {
            padding-top: 2rem;
        }
        
        @media (max-width: 991px) {
            #main-content > section:first-child,
            .hero-section,
            .privacy-policy-section,
            .terms-service-section,
            .about-hero-section {
                padding-top: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            #main-content > section:first-child,
            .hero-section,
            .privacy-policy-section,
            .terms-service-section,
            .about-hero-section {
                padding-top: 0.5rem;
            }
        }
        
        /* Skip to content link for accessibility */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: var(--primary-color);
            color: white;
            padding: 8px 16px;
            z-index: 1100;
            transition: top 0.3s;
            text-decoration: none;
        }
        
        .skip-link:focus {
            top: 0;
            color: white;
        }
        
        /* Fix for dropdown menus on mobile */
        @media (max-width: 991px) {
            .dropdown-menu {
                position: static !important;
                float: none;
                width: auto;
                margin-top: 0;
                background-color: transparent;
                border: 0;
                box-shadow: none;
            }
        }
        
        /* Smooth transitions */
        .navbar-collapse.collapsing {
            transition: height 0.35s ease;
        }
    </style>
</head>
<body>
    <!-- Accessibility skip link -->
    <a href="#main-content" class="skip-link">Skip to content</a>
    
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <!-- Company Logo/Brand -->
            <a class="navbar-brand" href="/" aria-label="ZetaRise Homepage">
                <?php if (file_exists('assets/images/logo.svg')): ?>
                    <img src="assets/images/logo.svg" alt="ZetaRise Logo" class="navbar-logo">
                <?php else: ?>
                    <i class="fas fa-cube me-2"></i>
                <?php endif; ?>
               ZetaRise Solutions
            </a>
            
            <!-- Mobile Menu Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <?php foreach($navItems as $item): ?>
                        <li class="nav-item">
                            <?php
                            $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                            $isActive = ($currentPath === $item['url']) || 
                                       ($item['url'] === '/' && ($currentPath === '/' || $currentPath === '/home'));
                            ?>
                            <a class="nav-link <?php echo $isActive ? 'active' : ''; ?>" 
                               href="<?php echo e($item['url']); ?>"><?php echo e($item['text']); ?></a>
                        </li>
                    <?php endforeach; ?>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a href="/contact" class="btn-primary-custom">Get Quote</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="main-content">
