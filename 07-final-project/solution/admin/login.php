<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Auth;

if (Auth::check()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (Auth::attempt($_POST['password'] ?? '')) {
        flash('Welcome back!');
        redirect('index.php');
    }
    $error = 'Wrong password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin login</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Admin login</h1>
    <?php if ($error): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <?= csrf_field() ?>
        <input type="password" name="password" placeholder="Password" autofocus>
        <button type="submit">Log in</button>
    </form>
    <p><a href="../">Back to the shop</a></p>
</body>
</html>
