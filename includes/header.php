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

// Get main navigation links
$navItems = [
    ['url' => 'index.php', 'text' => 'Home'],
    ['url' => 'services.php', 'text' => 'Services'],
    ['url' => 'about.php', 'text' => 'About'],
  
  
    ['url' => 'contact.php', 'text' => 'Contact']
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
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 0.75rem 0;
        }
        
        .navbar.scrolled {
            padding: 0.5rem 0;
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
        
        .navbar-nav .nav-link:hover::after {
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
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        @media (max-width: 991px) {
            .navbar-nav {
                text-align: center;
                padding: 20px 0;
            }
            
            .navbar-nav .nav-link {
                margin: 10px 0;
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
        }
        
        .skip-link:focus {
            top: 0;
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
            <a class="navbar-brand" href="index.php" aria-label="ZetaRise Homepage">
                <?php if (file_exists('assets/images/logo.svg')): ?>
                    <img src="assets/images/logo.svg" alt="ZetaRise Logo" class="navbar-logo">
                <?php else: ?>
                    <i class="fas fa-cube me-2"></i>
                <?php endif; ?>
               
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
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === $item['url'] ? 'active' : ''; ?>" 
                               href="<?php echo e($item['url']); ?>"><?php echo e($item['text']); ?></a>
                        </li>
                    <?php endforeach; ?>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a href="contact.php" class="btn-primary-custom">Get Quote</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="main-content">
