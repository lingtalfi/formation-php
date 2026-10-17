<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Auth;
use App\BookRepository;
use App\Database;

Auth::requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}
csrf_check();

$repo = new BookRepository(Database::connection());
$id = (int) ($_POST['id'] ?? 0);
$book = $repo->find($id);

if ($book !== null) {
    $repo->delete($id);
    flash("“{$book['title']}” deleted.");
}
redirect('index.php');
