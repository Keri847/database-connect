
<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/config/database.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$fullName = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verify_csrf()) {
        http_response_code(403);
        exit('Security check failed. Please go back and try again.');
    }

    $fullName = trim((string)($_POST['full_name'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');

    // Validate full name
    if ($fullName === '' || mb_strlen($fullName) > 100) {
        $errors[] = 'Enter your name (up to 100 characters).';
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
        $errors[] = 'Enter a valid email address.';
    }

    // Validate password
    if (strlen($password) < 10) {
        $errors[] = 'Use a password with at least 10 characters.';
    }

    // Register user if there are no validation errors
    if (!$errors) {

        // Check whether email already exists
        $check = $pdo->prepare(
            'SELECT id FROM users WHERE email = :email'
        );

        $check->execute(['email' => $email]);

        if ($check->fetch()) {

            $errors[] = 'An account with that email already exists.';

        } else {

            // Hash password securely
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $insert = $pdo->prepare(
                'INSERT INTO users (full_name, email, password)
                 VALUES (:full_name, :email, :password)'
            );

            $insert->execute([
                'full_name' => $fullName,
                'email' => $email,
                'password' => $hash
            ]);

            // Redirect after successful registration
            header('Location: index.php?registered=1');
            exit;
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create account</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<main class="auth-card">

    <div class="brand-mark">L</div>

    <h1>Create your account</h1>

    <p class="subtitle">
        It only takes a minute to get started.
    </p>

    <?php if ($errors): ?>
        <div class="alert error">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="register.php">

        <?= csrf_field() ?>

        <label for="full_name">Full name</label>

        <input
            id="full_name"
            name="full_name"
            type="text"
            autocomplete="name"
            maxlength="100"
            value="<?= e($fullName) ?>"
            required
        >

        <label for="email">Email address</label>

        <input
            id="email"
            name="email"
            type="email"
            autocomplete="email"
            maxlength="190"
            value="<?= e($email) ?>"
            required
        >

        <label for="password">Password</label>

        <input
            id="password"
            name="password"
            type="password"
            autocomplete="new-password"
            minlength="10"
            placeholder="At least 10 characters"
            required
        >

        <button class="primary-button" type="submit">
            Create account
        </button>

    </form>

    <p class="switch-link">
        Already registered?
        <a href="index.php">Sign in</a>
    </p>

</main>

</body>
</html>
