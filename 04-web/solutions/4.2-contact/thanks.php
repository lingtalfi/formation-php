<?php
session_start();

// Flash message: read it once, then remove it
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Thanks</title></head>
<body>
    <h1>Message sent</h1>
    <?php if ($flash): ?>
        <p><?= htmlspecialchars($flash) ?></p>
    <?php endif; ?>
    <p><a href="contact.php">Back to the form</a></p>
</body>
</html>
