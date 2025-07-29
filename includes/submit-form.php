<?php
require_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Method not allowed']));
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Replace deprecated FILTER_SANITIZE_STRING with htmlspecialchars
    $name = htmlspecialchars($data['name'] ?? '', ENT_QUOTES, 'UTF-8');
    $email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($data['message'] ?? '', ENT_QUOTES, 'UTF-8');
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }
    
    $stmt = $conn->prepare("INSERT INTO subscribers (name, email, message, ip_address, user_agent, referer_url) VALUES (?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }
    
    $stmt->bind_param("ssssss", $name, $email, $message, $ip, $userAgent, $referer);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Thank you for subscribing!']);
    } else {
        throw new Exception('Failed to save: ' . $stmt->error);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
