<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Auth;
use App\BookRepository;
use App\Database;

Auth::requireAdmin();   // first line of every admin page

$repo = new BookRepository(Database::connection());
extract(listing_params($repo, perPage: 10));
$flash = take_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin — Books</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>Books (<?= $total ?>)</h1>
        <div>
            <a href="stats.php">Statistics</a>
            <a href="../">View shop</a>
            <form class="inline" method="post" action="logout.php">
                <?= csrf_field() ?>
                <button type="submit">Log out</button>
            </form>
        </div>
    </header>

    <?php if ($flash): ?>
        <p class="flash"><?= e($flash) ?></p>
    <?php endif; ?>

    <p><a href="edit.php">+ Add a book</a></p>

    <form class="search" method="get">
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Title or author">
        <input type="hidden" name="sort" value="<?= e($sort) ?>">
        <input type="hidden" name="dir" value="<?= e($dir) ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th><?= sort_link('title', 'Title', $sort, $dir) ?></th>
            <th><?= sort_link('author', 'Author', $sort, $dir) ?></th>
            <th class="num"><?= sort_link('year', 'Year', $sort, $dir) ?></th>
            <th class="num"><?= sort_link('price', 'Price', $sort, $dir) ?></th>
            <th></th>
        </tr>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= e($book['title']) ?></td>
                <td><?= e($book['author']) ?></td>
                <td class="num"><?= e($book['year']) ?></td>
                <td class="num"><?= price($book['price']) ?></td>
                <td>
                    <a href="edit.php?id=<?= (int) $book['id'] ?>">Edit</a>
                    <!-- Deleting changes data: a POST form, never a simple link -->
                    <form class="inline" method="post" action="delete.php"
                          onsubmit="return confirm('Delete “<?= e($book['title']) ?>”?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $book['id'] ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?= pagination($page, $pages) ?>
</body>
</html>
