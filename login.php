<?php
// Minimal bootstrap
require_once __DIR__ . '/includes/db.php';

$pdo = getPDO();

// -------------------- Configuration --------------------
$MAX_ATTEMPTS = 10;            // allowed attempts
$ATTEMPT_WINDOW = 15 * 60;   // seconds (15 minutes)
$LOCKOUT_MINUTES = 15;       // lockout duration after repeated failures

// -------------------- Secure session --------------------
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '', // adjust if using a specific domain
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Strict',
]);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Helper: get client IP (simple)
function client_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

// Rate-limit check
function login_attempts_count(PDO $pdo, $username, $ip, $window_seconds) {
    $sql = "SELECT COUNT(*) AS c FROM login_attempts WHERE (username = :u OR ip = :ip) AND attempted_at >= (NOW() - INTERVAL :w SECOND)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':u', $username);
    $stmt->bindValue(':ip', $ip);
    $stmt->bindValue(':w', (int)$window_seconds, PDO::PARAM_INT);
    $stmt->execute();
    $r = $stmt->fetch();
    return (int)($r['c'] ?? 0);
}

function record_login_attempt(PDO $pdo, $username, $ip, $success) {
    $sql = "INSERT INTO login_attempts (username, ip, success, attempted_at) VALUES (:u, :ip, :s, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':u' => $username, ':ip' => $ip, ':s' => $success ? 1 : 0]);
}

// Create persistent session record
function create_user_session(PDO $pdo, $user_id) {
    $sid = session_id();
    $token = bin2hex(random_bytes(32));
    $ip = client_ip();
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $stmt = $pdo->prepare("INSERT INTO sessions (session_id, user_id, token, ip, user_agent, created_at, expires_at) VALUES (:sid, :uid, :token, :ip, :ua, NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR))");
    $stmt->execute([':sid'=>$sid, ':uid'=>$user_id, ':token'=>$token, ':ip'=>$ip, ':ua'=>$ua]);
    // store token in session for future verification
    $_SESSION['session_token'] = $token;
}

// POST handling
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf = $_POST['csrf'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'] ?? '', $csrf)) {
        $errors[] = "Invalid request (CSRF).";
    } elseif ($username === '' || $password === '') {
        $errors[] = "Provide username and password.";
    } else {
        $ip = client_ip();
        $attempts = login_attempts_count($pdo, $username, $ip, $ATTEMPT_WINDOW);
        if ($attempts >= $MAX_ATTEMPTS) {
            $errors[] = "Too many attempts. Try again later.";
        } else {
            // fetch user by username or email
$stmt = $pdo->prepare("
    SELECT id, username, email, password_hash, is_active 
    FROM users 
    WHERE username = :username OR email = :email 
    LIMIT 1
");

$stmt->execute([
    ':username' => $username,
    ':email'    => $username
]);

            $user = $stmt->fetch();

            $ok = false;
            if ($user && (int)($user['is_active'] ?? 0) === 1) {
                if (password_verify($password, $user['password_hash'])) {
                    $ok = true;
                }
            }

            record_login_attempt($pdo, $username, $ip, $ok);

            if ($ok) {
                // success: create session and redirect
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                create_user_session($pdo, $user['id']);
                // optional: clear old attempts for this user
                $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE (username = :u OR ip = :ip) AND attempted_at < (NOW() - INTERVAL :win SECOND)");
                $stmt->execute([':u'=>$username, ':ip'=>$ip, ':win' => $ATTEMPT_WINDOW * 2]);
                header("Location: /admin/dashboard.php");
                exit;
            } else {
                $errors[] = "Invalid credentials.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login — Zetarise</title>
<style>
/* Minimal glassy / web3 style */
:root{
  --bg:#0f1724;
  --panel: rgba(255,255,255,0.06);
  --accent: linear-gradient(90deg,#7c3aed,#06b6d4);
  --glass-border: rgba(255,255,255,0.06);
  --glass-shadow: 0 10px 30px rgba(2,6,23,0.7);
}
*{box-sizing:border-box}
body{
  margin:0;
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  background: radial-gradient(ellipse at 10% 20%, rgba(124,58,237,0.12), transparent 10%),
              radial-gradient(ellipse at 90% 80%, rgba(6,182,212,0.08), transparent 10%),
              var(--bg);
  font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
  color:#e6eef8;
}
.container{
  width:420px;
  padding:28px;
  border-radius:16px;
  background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.02));
  border:1px solid var(--glass-border);
  box-shadow: var(--glass-shadow);
  backdrop-filter: blur(8px) saturate(120%);
  transform: translateZ(0);
}
.header{
  display:flex;align-items:center;gap:12px;margin-bottom:18px;
}
.logo{
  width:52px;height:52px;border-radius:12px;
  background:linear-gradient(135deg,#7c3aed,#06b6d4);
  display:flex;align-items:center;justify-content:center;font-weight:700;box-shadow:0 6px 18px rgba(12,18,40,0.6);
}
h1{font-size:18px;margin:0}
.form-group{margin-bottom:12px}
.input{
  width:100%;padding:12px 14px;border-radius:10px;border:1px solid rgba(255,255,255,0.04);
  background:rgba(0,0,0,0.25);color:inherit;font-size:14px;
  outline:none;transition:box-shadow .12s, transform .08s;
}
.input:focus{box-shadow:0 6px 18px rgba(7,12,30,0.6);transform:translateY(-1px)}
.btn{
  width:100%;padding:12px 14px;border-radius:10px;border:0;cursor:pointer;
  background-image: var(--accent); color:white; font-weight:600; font-size:15px;
  box-shadow:0 8px 24px rgba(12,18,40,0.6);
}
.footer{font-size:12px;opacity:0.8;margin-top:12px;text-align:center}
.error{background:rgba(220,38,38,0.12);color:#ffc1c1;padding:8px;border-radius:8px;margin-bottom:12px;font-size:13px}
.hint{opacity:0.8;font-size:13px;margin-bottom:8px}
.small{font-size:12px;opacity:0.7}
.brandline{font-size:13px;opacity:0.7}
</style>
</head>
<body>
  <main class="container" role="main" aria-labelledby="login-heading">
    <div class="header">
      <div class="logo">ZR</div>
      <div>
        <h1 id="login-heading">Admin Portal</h1>
        <div class="brandline">Manage content, SEO, and contacts</div>
      </div>
    </div>

    <?php if (!empty($errors)): ?>
      <div class="error"><?= e(implode(' ', $errors)) ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off" novalidate>
      <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf_token']) ?>">
      <div class="form-group">
        <label class="small">Username or Email</label>
        <input class="input" name="username" type="text" inputmode="email" required autofocus maxlength="150" value="<?= e($_POST['username'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="small">Password</label>
        <input class="input" name="password" type="password" required maxlength="128" autocomplete="current-password">
      </div>
      <div class="form-group">
        <button class="btn" type="submit">Sign in</button>
      </div>
      <div class="footer small">Protected area — authorized admins only. Session expires after inactivity.</div>
    </form>
  </main>
</body>
</html>
