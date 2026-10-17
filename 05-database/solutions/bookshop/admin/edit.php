<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Auth;
use App\BookRepository;
use App\BookValidator;
use App\Database;

Auth::requireAdmin();

$repo = new BookRepository(Database::connection());

// Same page for "add" (no id) and "edit" (?id=12)
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT) ?: null;
$book = ['title' => '', 'author' => '', 'year' => '', 'price' => ''];

if ($id !== null) {
    $book = $repo->find($id);
    if ($book === null) {
        http_response_code(404);
        exit('Book not found. <a href="index.php">Back</a>');
    }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    [$data, $errors] = BookValidator::validate($_POST);

    if (!$errors) {
        if ($id === null) {
            $repo->create($data);
            flash("“{$data['title']}” added.");
        } else {
            $repo->update($id, $data);
            flash("“{$data['title']}” updated.");
        }
        redirect('index.php');
    }

    $book = $_POST;   // redisplay what the user typed, with the errors
}

/** Small helper: one form field with its error. */
function field(string $name, string $label, array $book, array $errors, string $type = 'text'): string
{
    $html = '<p><label>' . e($label) . '<br>'
          . '<input type="' . $type . '" name="' . $name . '" value="' . e($book[$name] ?? '') . '">'
          . '</label>';
    if (isset($errors[$name])) {
        $html .= '<br><span class="error">' . e($errors[$name]) . '</span>';
    }
    return $html . '</p>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $id ? 'Edit' : 'Add' ?> a book</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1><?= $id ? 'Edit' : 'Add' ?> a book</h1>

    <form method="post">
        <?= csrf_field() ?>
        <?= field('title', 'Title', $book, $errors) ?>
        <?= field('author', 'Author', $book, $errors) ?>
        <?= field('year', 'Year (optional)', $book, $errors) ?>
        <?= field('price', 'Price (€)', $book, $errors) ?>
        <button type="submit">Save</button>
        <a href="index.php">Cancel</a>
    </form>
</body>
</html>
