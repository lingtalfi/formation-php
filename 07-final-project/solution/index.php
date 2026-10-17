<?php
require __DIR__ . '/src/bootstrap.php';

use App\BookRepository;
use App\Database;
use App\Tracker;

Tracker::start();   // record this page view (public pages only: admin pages are not tracked)

$repo = new BookRepository(Database::connection());

// $q, $sort, $dir, $total, $pages, $page, $books
extract(listing_params($repo, perPage: 8));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Bookshop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Bookshop</h1>
        <a href="admin/">Admin</a>
    </header>

    <!-- GET form: the search ends up in the URL, so results can be bookmarked and shared -->
    <form class="search" method="get">
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Title or author">
        <!-- keep the current sort when searching -->
        <input type="hidden" name="sort" value="<?= e($sort) ?>">
        <input type="hidden" name="dir" value="<?= e($dir) ?>">
        <button type="submit">Search</button>
        <?php if ($q !== ''): ?>
            <a href="?">Clear</a>
        <?php endif; ?>
    </form>

    <p class="muted">
        <?= $total ?> book(s)<?= $q !== '' ? ' matching “' . e($q) . '”' : '' ?>
        — page <?= $page ?> of <?= $pages ?>
    </p>

    <?php if ($books): ?>
        <table>
            <tr>
                <th><?= sort_link('title', 'Title', $sort, $dir) ?></th>
                <th><?= sort_link('author', 'Author', $sort, $dir) ?></th>
                <th class="num"><?= sort_link('year', 'Year', $sort, $dir) ?></th>
                <th class="num"><?= sort_link('price', 'Price', $sort, $dir) ?></th>
            </tr>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= e($book['title']) ?></td>
                    <td><?= e($book['author']) ?></td>
                    <td class="num"><?= e($book['year']) ?></td>
                    <td class="num"><?= price($book['price']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No book found.</p>
    <?php endif; ?>

    <?= pagination($page, $pages) ?>
</body>
</html>
