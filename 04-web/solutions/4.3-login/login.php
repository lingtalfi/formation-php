<?php
session_start();

const USERNAME = 'admin';
// Generated with make-hash.php — never store the clear password
const PASSWORD_HASH = '$2y$10$yzpM.4yBZ4gY3kkCJQYhVO85itk1DhJcKbXu5QxG7ksrz6e7Nbx62';

// Already logged in? Go straight to the dashboard
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === USERNAME && password_verify($password, PASSWORD_HASH)) {
        session_regenerate_id(true);   // new session id after login: prevents session fixation
        $_SESSION['user'] = $username;
        header('Location: dashboard.php');
        exit;
    }
    // Same message for wrong user and wrong password: don't help attackers
    $error = 'Invalid credentials.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Login</title></head>
<body>
    <h1>Login</h1>
    <?php if ($error): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <p><label>Username<br><input name="username" value="<?= htmlspecialchars($username) ?>"></label></p>
        <p><label>Password<br><input type="password" name="password"></label></p>
        <button type="submit">Log in</button>
    </form>
</body>
</html>
