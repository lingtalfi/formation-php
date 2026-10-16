<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;   // without exit, the rest of the page would still run and be sent!
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Dashboard</title></head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user']) ?></h1>
    <p><a href="logout.php">Log out</a></p>
</body>
</html>
