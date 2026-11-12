
<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!verify_csrf()) {
    http_response_code(403);
    exit('Security check failed. Please reload the sign-in page.');
}

$email = strtolower(trim((string)($_POST['email'] ?? '')));
$password = (string)($_POST['password'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    header('Location: index.php?error=1');
    exit;
}

// Get user from database
$stmt = $pdo->prepare(
    "SELECT id, full_name, email, password
     FROM users
     WHERE email = ?"
);

$stmt->execute([$email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verify password
if (!$user || !password_verify($password, $user['password'])) {
    header('Location: index.php?error=1');
    exit;
}

// Upgrade password hash if necessary
if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
    $newHash = password_hash($password, PASSWORD_DEFAULT);

    $update = $pdo->prepare(
        'UPDATE users SET password = :hash WHERE id = :id'
    );

    $update->execute([
        'hash' => $newHash,
        'id' => $user['id']
    ]);
}

// Start authenticated session
session_regenerate_id(true);

$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

header('Location: dashboard.php');
exit;
