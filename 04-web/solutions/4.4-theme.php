<?php
$allowed = ['light', 'dark'];

// A theme was clicked: validate, store in a cookie, redirect to the clean URL
if (isset($_GET['theme'])) {
    if (in_array($_GET['theme'], $allowed, true)) {
        setcookie('theme', $_GET['theme'], [
            'expires'  => time() + 60 * 60 * 24 * 30,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$theme = $_COOKIE['theme'] ?? 'light';
if (!in_array($theme, $allowed, true)) {
    $theme = 'light';   // the cookie can be modified by the user: re-check it
}

$colors = $theme === 'dark'
    ? ['bg' => '#1e1e1e', 'fg' => '#eeeeee']
    : ['bg' => '#ffffff', 'fg' => '#222222'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Theme</title>
    <style>
        body { background: <?= $colors['bg'] ?>; color: <?= $colors['fg'] ?>; font-family: sans-serif; }
        a { color: inherit; }
    </style>
</head>
<body>
    <h1>Current theme: <?= $theme ?></h1>
    <p><a href="?theme=light">Light</a> | <a href="?theme=dark">Dark</a></p>
</body>
</html>
