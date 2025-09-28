<?php
/*$servername = "u829661811_web_zeta";
$username = "u829661811_admin";
$password = "Gn=RtVWAk[4";
$dbname = "zetarise";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

*/
?>

<?php
// Replaced mysqli code with a secure PDO helper and common helpers.

$DB_CONFIG = [
    'host' => '127.0.0.1',
    'db'   => 'zetarise',
    'user' => 'root',
    'pass' => 'root',
    'charset' => 'utf8mb4',
];

// Returns a PDO instance. Use getPDO() everywhere instead of creating new connections.
function getPDO(): PDO {
    global $DB_CONFIG;
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $host = $DB_CONFIG['host'];
    $db   = $DB_CONFIG['db'];
    $user = $DB_CONFIG['user'];
    $pass = $DB_CONFIG['pass'];
    $charset = $DB_CONFIG['charset'];

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+05:30'",
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Do not expose DB details in production. Log error and show generic message.
        error_log("DB connection failed: " . $e->getMessage());
        // Fail fast in development; adjust for production.
        die("Database connection failed.");
    }
}

// Basic output escaping helper
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Count records in a table
function countTable(PDO $pdo, string $table, string $condition = '1=1'): int {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM $table WHERE $condition");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    } catch (PDOException $e) {
        error_log("Error counting table $table: " . $e->getMessage());
        return 0;
    }
}

// Get recent contact submissions
function getRecentContactSubmissions(PDO $pdo, int $limit = 5): array {
    try {
        $stmt = $pdo->prepare("
            SELECT id, name, email, subject, submitted_at 
            FROM contact_submissions 
            ORDER BY submitted_at DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching recent submissions: " . $e->getMessage());
        return [];
    }
}

// Get visitors count (last 30 days by default)
function getVisitorsCount(PDO $pdo, int $days = 30): int {
    try {
        $stmt = $pdo->prepare("
            SELECT COUNT(DISTINCT visitor_ip) AS count 
            FROM site_analytics 
            WHERE visit_date >= DATE_SUB(CURRENT_DATE(), INTERVAL :days DAY)
        ");
        $stmt->bindValue(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    } catch (PDOException $e) {
        error_log("Error counting visitors: " . $e->getMessage());
        return 0;
    }
}

// Get all settings as an associative array
function getSettings(PDO $pdo, string $group = null): array {
    try {
        $sql = "SELECT setting_key, setting_value FROM website_settings WHERE is_public = 1";
        if ($group !== null) {
            $sql .= " AND setting_group = :group";
        }
        $stmt = $pdo->prepare($sql);
        if ($group !== null) {
            $stmt->bindValue(':group', $group);
        }
        $stmt->execute();
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    } catch (PDOException $e) {
        error_log("Error fetching settings: " . $e->getMessage());
        return [];
    }
}

// Get a single setting value
function getSetting(PDO $pdo, string $key, $default = null) {
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM website_settings WHERE setting_key = :key LIMIT 1");
        $stmt->bindValue(':key', $key);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    } catch (PDOException $e) {
        error_log("Error fetching setting '$key': " . $e->getMessage());
        return $default;
    }
}

// Save a setting value
function saveSetting(PDO $pdo, string $key, $value, string $group = 'general', bool $isPublic = true): bool {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO website_settings (setting_key, setting_value, setting_group, is_public) 
            VALUES (:key, :value, :group, :is_public)
            ON DUPLICATE KEY UPDATE setting_value = :value, setting_group = :group, is_public = :is_public
        ");
        return $stmt->execute([
            ':key' => $key,
            ':value' => $value,
            ':group' => $group,
            ':is_public' => $isPublic ? 1 : 0
        ]);
    } catch (PDOException $e) {
        error_log("Error saving setting '$key': " . $e->getMessage());
        return false;
    }
}

