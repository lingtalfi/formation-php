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

// --- Pagination (bonus)
const PER_PAGE = 5;

$total = $repo->count();
$pages = max(1, (int) ceil($total / PER_PAGE));   // at least 1 page, even when empty

// ?page= comes from the URL: validate it (must be an integer >= 1), then keep it in range
$page = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;
$page = min($page, $pages);

$messages = $repo->findPage(PER_PAGE, ($page - 1) * PER_PAGE);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Guestbook (<?= $total ?> messages)</title>
    <style>
        body { font-family: sans-serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; }
        .error { color: #c00; }
        .flash { background: #e6f6e6; padding: .5rem; }
        article { border-bottom: 1px solid #ddd; padding: .5rem 0; }
        small { color: #777; }
        nav.pages { margin: 1rem 0; }
        nav.pages a, nav.pages span { padding: .2rem .5rem; }
        nav.pages .current { font-weight: bold; background: #eee; }
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

    <h2><?= $total ?> message(s)</h2>
    <?php foreach ($messages as $m): ?>
        <article>
            <strong><?= e($m['author']) ?></strong>
            <small><?= (new DateTime($m['created_at']))->format('d/m/Y H:i') ?></small>
            <p><?= nl2br(e($m['content'])) ?></p>
        </article>
    <?php endforeach; ?>

    <?php if ($pages > 1): ?>
        <nav class="pages">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">&laquo; Newer</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="current"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $pages): ?>
                <a href="?page=<?= $page + 1 ?>">Older &raquo;</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>

    <p><a href="admin.php">Admin</a></p>
</body>
</html>
