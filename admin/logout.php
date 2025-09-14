<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

// Start session if not already started
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Log the user out
logoutUser();

// Redirect to login page
header('Location: /login.php');
exit;
