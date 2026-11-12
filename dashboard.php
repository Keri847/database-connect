<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/config/database.php';
require_login();

$stmt = $pdo->prepare(
    'SELECT full_name, email, created_at FROM users WHERE id = :id LIMIT 1'
);
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="dashboard-card">
    <div class="dashboard-top">
        <div>
            <p class="eyebrow">ACCOUNT OVERVIEW</p>
            <h1>Hello, <?= e($user['full_name']) ?>!</h1>
            <p class="subtitle">You are signed in successfully.</p>
        </div>
        <div class="avatar"><?= e(strtoupper(substr($user['full_name'], 0, 1))) ?></div>
    </div>

    <section class="profile-panel">
        <h2>Profile details</h2>
        <div class="detail-row"><span>Full name</span><strong><?= e($user['full_name']) ?></strong></div>
        <div class="detail-row"><span>Email</span><strong><?= e($user['email']) ?></strong></div>
        <div class="detail-row"><span>Member since</span><strong><?= e($user['created_at']) ?></strong></div>
    </section>

    <form action="logout.php" method="post">
        <?= csrf_field() ?>
        <button class="logout-button" type="submit">Log out</button>
    </form>
</main>
</body>
</html>
