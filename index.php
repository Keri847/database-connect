<?php
require_once __DIR__ . '/config/bootstrap.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$notice = '';
if (isset($_GET['registered'])) {
    $notice = 'Your account was created. You can now sign in.';
} elseif (isset($_GET['logged_out'])) {
    $notice = 'You have been logged out.';
}
$error = isset($_GET['error']) ? 'Email or password is incorrect.' : '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="auth-card">
    <div class="brand-mark">L</div>
    <h1>Welcome back</h1>
    <p class="subtitle">Sign in to continue to your account.</p>

    <?php if ($notice): ?><div class="alert success"><?= e($notice) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>

    <form action="login.php" method="post">
        <?= csrf_field() ?>
        <label for="email">Email address</label>
        <input id="email" name="email" type="email" autocomplete="email"
               placeholder="you@example.com" maxlength="190" required>

        <label for="password">Password</label>
        <div class="password-wrap">
            <input id="password" name="password" type="password"
                   autocomplete="current-password" placeholder="Your password" required>
            <button class="toggle" type="button" id="togglePassword">Show</button>
        </div>

        <button class="primary-button" type="submit">Sign in</button>
    </form>

    <p class="switch-link">New here? <a href="register.php">Create an account</a></p>
</main>
<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const field = document.getElementById('password');
    const showing = field.type === 'text';
    field.type = showing ? 'password' : 'text';
    this.textContent = showing ? 'Show' : 'Hide';
});
</script>
</body>
</html>
