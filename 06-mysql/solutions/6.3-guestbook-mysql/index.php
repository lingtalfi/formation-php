<?php
require __DIR__ . '/src/bootstrap.php';

use App\Database;
use App\MessageRepository;

$repo = new MessageRepository(Database::connection());

$errors = [];
$author = '';
$content = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = trim($_POST['author'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($author === '' || mb_strlen($author) > 50) {
        $errors['author'] = 'Name is required (50 characters max).';
    }
    if (mb_strlen($content) < 3) {
        $errors['content'] = 'Message must be at least 3 characters.';
    }

    if (!$errors) {
        $repo->add($author, $content);
        $_SESSION['flash'] = 'Thanks for your message!';
        redirect('index.php');   // Post/Redirect/Get
    }
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$messages = $repo->findAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Guestbook (<?= count($messages) ?> messages)</title>
    <style>
        body { font-family: sans-serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; }
        .error { color: #c00; }
        .flash { background: #e6f6e6; padding: .5rem; }
        article { border-bottom: 1px solid #ddd; padding: .5rem 0; }
        small { color: #777; }
    </style>
</head>
<body>
    <h1>Guestbook</h1>

    <?php if ($flash): ?>
        <p class="flash"><?= e($flash) ?></p>
    <?php endif; ?>

    <form method="post">
        <p>
            <label>Your name<br><input name="author" value="<?= e($author) ?>"></label>
            <?php if (isset($errors['author'])): ?><br><span class="error"><?= e($errors['author']) ?></span><?php endif; ?>
        </p>
        <p>
            <label>Message<br><textarea name="content" rows="4" cols="50"><?= e($content) ?></textarea></label>
            <?php if (isset($errors['content'])): ?><br><span class="error"><?= e($errors['content']) ?></span><?php endif; ?>
        </p>
        <button type="submit">Sign the guestbook</button>
    </form>

    <h2><?= count($messages) ?> message(s)</h2>
    <?php foreach ($messages as $m): ?>
        <article>
            <strong><?= e($m['author']) ?></strong>
            <small><?= (new DateTime($m['created_at']))->format('d/m/Y H:i') ?></small>
            <p><?= nl2br(e($m['content'])) ?></p>
        </article>
    <?php endforeach; ?>

    <p><a href="admin.php">Admin</a></p>
</body>
</html>
