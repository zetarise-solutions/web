<?php
// Prevent direct access without error code
if (!isset($_GET['code']) && !isset($_SERVER['REDIRECT_STATUS'])) {
    header('HTTP/1.1 404 Not Found');
    $errorCode = 404;
} else {
    $errorCode = $_GET['code'] ?? $_SERVER['REDIRECT_STATUS'] ?? 500;
}

// Error definitions
$errors = [
    400 => [
        'title' => 'Bad Request',
        'message' => 'The server cannot process your request due to invalid syntax.',
        'description' => 'This usually happens when the request is malformed or contains invalid parameters. Please check your request and try again.',
        'icon' => 'fa-exclamation-triangle'
    ],
    401 => [
        'title' => 'Unauthorized', 
        'message' => 'You need to authenticate to access this resource.',
        'description' => 'This page requires authentication. Please log in with valid credentials to continue.',
        'icon' => 'fa-lock'
    ],
    403 => [
        'title' => 'Access Forbidden',
        'message' => 'You don\'t have permission to access this resource.',
        'description' => 'This could be due to insufficient privileges, geographic restrictions, or security policies. If you believe this is an error, please contact support.',
        'icon' => 'fa-ban'
    ],
    404 => [
        'title' => 'Page Not Found',
        'message' => 'The page you\'re looking for doesn\'t exist.',
        'description' => 'The page may have been moved, deleted, or the URL might be incorrect. Please check the URL or navigate back to our homepage.',
        'icon' => 'fa-search'
    ],
    405 => [
        'title' => 'Method Not Allowed',
        'message' => 'The request method is not allowed for this resource.',
        'description' => 'The HTTP method used is not supported for this endpoint. Please check the API documentation.',
        'icon' => 'fa-times-circle'
    ],
    408 => [
        'title' => 'Request Timeout',
        'message' => 'The server timed out waiting for the request.',
        'description' => 'Your request took too long to complete. This might be due to a slow connection or server overload. Please try again.',
        'icon' => 'fa-clock'
    ],
    410 => [
        'title' => 'Gone',
        'message' => 'The requested resource is no longer available.',
        'description' => 'This resource has been permanently removed and is no longer accessible. Please update your bookmarks.',
        'icon' => 'fa-trash'
    ],
    413 => [
        'title' => 'Payload Too Large',
        'message' => 'The request payload is too large.',
        'description' => 'The file or data you\'re trying to upload exceeds the maximum allowed size. Please reduce the size and try again.',
        'icon' => 'fa-weight-hanging'
    ],
    414 => [
        'title' => 'URI Too Long',
        'message' => 'The request URI is too long.',
        'description' => 'The URL you\'re trying to access is too long for the server to process. Please use a shorter URL.',
        'icon' => 'fa-link'
    ],
    429 => [
        'title' => 'Too Many Requests',
        'message' => 'You have sent too many requests in a short period.',
        'description' => 'Please slow down your requests. Wait a moment before trying again to avoid being rate-limited.',
        'icon' => 'fa-tachometer-alt'
    ],
    500 => [
        'title' => 'Internal Server Error',
        'message' => 'Something went wrong on our end.',
        'description' => 'We\'re experiencing technical difficulties. Our team has been notified and is working to resolve the issue. Please try again later.',
        'icon' => 'fa-server'
    ],
    502 => [
        'title' => 'Bad Gateway',
        'message' => 'The server received an invalid response.',
        'description' => 'There\'s a communication problem between our servers. We\'re working to resolve this issue. Please try again later.',
        'icon' => 'fa-network-wired'
    ],
    503 => [
        'title' => 'Service Unavailable',
        'message' => 'The service is temporarily unavailable.',
        'description' => 'Our servers are currently undergoing maintenance or experiencing high load. Please check back in a few minutes.',
        'icon' => 'fa-tools'
    ],
    504 => [
        'title' => 'Gateway Timeout',
        'message' => 'The server took too long to respond.',
        'description' => 'The server didn\'t receive a timely response. This might be temporary. Please try again in a moment.',
        'icon' => 'fa-hourglass-half'
    ]
];

// Get error details or default to 500
$error = $errors[$errorCode] ?? $errors[500];
$pageTitle = "Error {$errorCode} - {$error['title']} | ZetaRise Solutions";

// Set appropriate HTTP status
http_response_code($errorCode);

// Prepare details for logging
$uri = $_SERVER['REQUEST_URI'] ?? 'unknown';
$ip  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$time = date('Y-m-d H:i:s');

// Log the error for monitoring
error_log("HTTP Error {$errorCode}: {$uri} from {$ip} at {$time}");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Prevent indexing of error pages -->
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #3b82f6;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f8fafc;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        
        .error-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            padding: 3rem 2rem;
            max-width: 600px;
            width: 100%;
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .error-icon {
            width: 100px;
            height: 100px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: pulse 2s infinite;
        }
        
        .error-icon i {
            font-size: 2.5rem;
            color: white;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .error-code {
            font-family: 'Poppins', sans-serif;
            font-size: 4rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            line-height: 1;
        }
        
        .error-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1.2rem;
            color: var(--text-dark);
            margin-bottom: 1rem;
            font-weight: 500;
        }
        
        .error-description {
            color: var(--text-light);
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }
        
        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
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
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
            color: white;
        }
        
        .btn-outline-custom {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
        }
        
        .btn-outline-custom:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }
        
        .error-info {
            background: var(--bg-light);
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .error-info-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .error-info-list {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }
        
        .error-info-list li {
            padding: 0.3rem 0;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .error-info-list i {
            color: var(--primary-color);
            width: 16px;
        }
        
        .brand-logo {
            position: absolute;
            top: 2rem;
            left: 2rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        @media (max-width: 768px) {
            .error-card {
                padding: 2rem 1.5rem;
            }
            
            .error-code {
                font-size: 3rem;
            }
            
            .error-title {
                font-size: 1.5rem;
            }
            
            .error-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .brand-logo {
                position: static;
                justify-content: center;
                margin-bottom: 2rem;
                color: var(--text-dark);
            }
        }
        
        .countdown {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <a href="/" class="brand-logo">
        <i class="fas fa-cube"></i>
        ZetaRise Solutions
    </a>
    
    <div class="error-container">
        <div class="error-card">
            <div class="error-icon">
                <i class="fas <?php echo $error['icon']; ?>"></i>
            </div>
            
            <div class="error-code"><?php echo $errorCode; ?></div>
            <h1 class="error-title"><?php echo htmlspecialchars($error['title']); ?></h1>
            <p class="error-message"><?php echo htmlspecialchars($error['message']); ?></p>
            <p class="error-description"><?php echo htmlspecialchars($error['description']); ?></p>
            
            <div class="error-actions">
                <a href="/" class="btn-primary-custom">
                    <i class="fas fa-home"></i>
                    Go Home
                </a>
                <a href="javascript:history.back()" class="btn-outline-custom">
                    <i class="fas fa-arrow-left"></i>
                    Go Back
                </a>
                <a href="/contact" class="btn-outline-custom">
                    <i class="fas fa-envelope"></i>
                    Contact Support
                </a>
            </div>
            
            <?php if ($errorCode == 404): ?>
            <div class="error-info">
                <div class="error-info-title">What you can do:</div>
                <ul class="error-info-list">
                    <li><i class="fas fa-check"></i> Check the URL for typos</li>
                    <li><i class="fas fa-check"></i> Use the navigation menu</li>
                    <li><i class="fas fa-check"></i> Search our site</li>
                    <li><i class="fas fa-check"></i> Contact us for help</li>
                </ul>
            </div>
            <?php elseif ($errorCode >= 500): ?>
            <div class="error-info">
                <div class="error-info-title">We're working on it:</div>
                <ul class="error-info-list">
                    <li><i class="fas fa-check"></i> Our team has been notified</li>
                    <li><i class="fas fa-check"></i> We're investigating the issue</li>
                    <li><i class="fas fa-check"></i> Service should resume shortly</li>
                    <li><i class="fas fa-check"></i> No data has been lost</li>
                </ul>
            </div>
            <?php elseif ($errorCode == 403): ?>
            <div class="error-info">
                <div class="error-info-title">Possible reasons:</div>
                <ul class="error-info-list">
                    <li><i class="fas fa-info"></i> Geographic restrictions</li>
                    <li><i class="fas fa-info"></i> Insufficient permissions</li>
                    <li><i class="fas fa-info"></i> Security policies</li>
                    <li><i class="fas fa-info"></i> Account limitations</li>
                </ul>
            </div>
            <?php endif; ?>
            
            <?php if (in_array($errorCode, [503, 500, 502])): ?>
            <div class="countdown" id="countdown">
                Auto-refresh in <span id="timer">30</span> seconds...
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Auto-refresh for server errors
        <?php if (in_array($errorCode, [503, 500, 502])): ?>
        let timeLeft = 30;
        const timer = document.getElementById('timer');
        const countdown = setInterval(() => {
            timeLeft--;
            if (timer) timer.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                window.location.reload();
            }
        }, 1000);
        
        // Stop countdown if user interacts with page
        document.addEventListener('click', () => clearInterval(countdown));
        document.addEventListener('keydown', () => clearInterval(countdown));
        <?php endif; ?>
        
        // Track error for analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'exception', {
                'description': 'HTTP_<?php echo $errorCode; ?>',
                'fatal': <?php echo $errorCode >= 500 ? 'true' : 'false'; ?>
            });
        }
    </script>
</body>
</html>