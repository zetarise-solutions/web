<?php
/**
 * Authentication helpers for the admin dashboard
 */

// Ensure a user is authenticated or redirect to login
function ensureUserAuthenticated() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    
    // Check if user is logged in via session
    if (empty($_SESSION['user_id'])) {
        header('Location: /login.php');
        exit;
    }
    
    // Validate session token against database
    if (!validateSessionToken()) {
        // Session token invalid or expired, destroy session and redirect
        session_unset();
        session_destroy();
        header('Location: /login.php?error=session_expired');
        exit;
    }
    
    // Extend session if needed
    extendSession();
}

// Get the currently authenticated user data
function getCurrentUser(): array {
    $pdo = getPDO();
    $userId = $_SESSION['user_id'] ?? null;
    
    if (!$userId) {
        return [];
    }
    
    try {
        $stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE id = :id AND is_active = 1");
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch();
        return $user ?: [];
    } catch (PDOException $e) {
        error_log("Error fetching user data: " . $e->getMessage());
        return [];
    }
}

// Validate the session token against the database
function validateSessionToken(): bool {
    $pdo = getPDO();
    $sessionId = session_id();
    $token = $_SESSION['session_token'] ?? null;
    $userId = $_SESSION['user_id'] ?? null;
    
    if (!$sessionId || !$token || !$userId) {
        return false;
    }
    
    try {
        $stmt = $pdo->prepare("
            SELECT id FROM sessions 
            WHERE session_id = :sid 
            AND user_id = :uid 
            AND token = :token 
            AND expires_at > NOW()
        ");
        $stmt->execute([
            ':sid' => $sessionId,
            ':uid' => $userId,
            ':token' => $token
        ]);
        return (bool)$stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error validating session: " . $e->getMessage());
        return false;
    }
}

// Extend the current session if needed
function extendSession(): void {
    $pdo = getPDO();
    $sessionId = session_id();
    
    try {
        $stmt = $pdo->prepare("
            UPDATE sessions 
            SET expires_at = DATE_ADD(NOW(), INTERVAL 1 HOUR) 
            WHERE session_id = :sid 
            AND expires_at < DATE_ADD(NOW(), INTERVAL 30 MINUTE)
        ");
        $stmt->execute([':sid' => $sessionId]);
    } catch (PDOException $e) {
        error_log("Error extending session: " . $e->getMessage());
    }
}

// Log out the current user
function logoutUser(): void {
    $pdo = getPDO();
    $sessionId = session_id();
    
    try {
        // Remove session from database
        $stmt = $pdo->prepare("DELETE FROM sessions WHERE session_id = :sid");
        $stmt->execute([':sid' => $sessionId]);
    } catch (PDOException $e) {
        error_log("Error during logout: " . $e->getMessage());
    }
    
    // Clear session variables and destroy session
    session_unset();
    session_destroy();
}
