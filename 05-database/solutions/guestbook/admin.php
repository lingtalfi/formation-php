<?php
require __DIR__ . '/src/bootstrap.php';

use App\Database;
use App\MessageRepository;

// Password: admin123 (hash generated with password_hash)
const ADMIN_HASH = '$2y$10$aC0ySWFPGLLaDwkSdclzjubeCe93WsDV1DFPFVNKKmer9.H/nRMsy';

$error = '';

// --- Login
if (!isset($_SESSION['admin'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && password_verify($_POST['password'] ?? '', ADMIN_HASH)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        redirect('admin.php');
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $error = 'Wrong password.';
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head><meta charset="utf-8"><title>Admin login</title></head>
    <body>
        <h1>Admin login</h1>
        <?php if ($error): ?><p style="color:#c00"><?= e($error) ?></p><?php endif; ?>
        <form method="post">
            <input type="password" name="password" placeholder="Password">
            <button>Log in</button>
        </form>
        <p><a href="index.php">Back</a></p>
    </body>
    </html>
    <?php
    exit;
}

// --- Logged in: handle deletion
$repo = new MessageRepository(Database::connection());

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $repo->delete((int) $_POST['delete_id']);
    redirect('admin.php');
}

$messages = $repo->findAll();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Admin</title></head>
<body>
    <h1>Moderation (<?= count($messages) ?>)</h1>
    <p><a href="index.php">Guestbook</a> | <a href="logout.php">Log out</a></p>
    <table border="1" cellpadding="4">
        <tr><th>Author</th><th>Message</th><th>Date</th><th></th></tr>
        <?php foreach ($messages as $m): ?>
            <tr>
                <td><?= e($m['author']) ?></td>
                <td><?= e($m['content']) ?></td>
                <td><?= e($m['created_at']) ?></td>
                <td>
                    <form method="post" onsubmit="return confirm('Delete this message?')">
                        <input type="hidden" name="delete_id" value="<?= (int) $m['id'] ?>">
                        <button>Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
