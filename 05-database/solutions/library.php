<?php
declare(strict_types=1);

// 1. Connection
$pdo = new PDO('sqlite:' . __DIR__ . '/library.db', null, null, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

// 2. Table
$pdo->exec('CREATE TABLE IF NOT EXISTS books (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    author TEXT NOT NULL,
    year INTEGER
)');

// 3. Seed data, only if empty
if ((int) $pdo->query('SELECT COUNT(*) FROM books')->fetchColumn() === 0) {
    $books = [
        ['1984', 'George Orwell', 1949],
        ['Dune', 'Frank Herbert', 1965],
        ['The Hobbit', 'J.R.R. Tolkien', 1937],
        ['Neuromancer', 'William Gibson', 1984],
        ['Foundation', 'Isaac Asimov', 1951],
    ];
    $insert = $pdo->prepare('INSERT INTO books (title, author, year) VALUES (?, ?, ?)');
    foreach ($books as $book) {
        $insert->execute($book);   // prepared once, executed 5 times
    }
}

function displayBooks(iterable $books): void
{
    foreach ($books as $b) {
        echo "{$b['year']} - {$b['title']} ({$b['author']})" . PHP_EOL;
    }
}

// 4. All books by year
echo '== All books' . PHP_EOL;
displayBooks($pdo->query('SELECT * FROM books ORDER BY year'));

// 5. Count
echo 'Total: ' . $pdo->query('SELECT COUNT(*) FROM books')->fetchColumn() . PHP_EOL;

// 6. After 1950
echo '== After 1950' . PHP_EOL;
$stmt = $pdo->prepare('SELECT * FROM books WHERE year > :year ORDER BY year');
$stmt->execute(['year' => 1950]);
displayBooks($stmt->fetchAll());

// 7. Update
$stmt = $pdo->prepare('UPDATE books SET year = :year WHERE title = :title');
$stmt->execute(['year' => 1966, 'title' => 'Dune']);
echo 'Updated rows: ' . $stmt->rowCount() . PHP_EOL;

// 8. Delete
$stmt = $pdo->prepare('DELETE FROM books WHERE title = ?');
$stmt->execute(['Neuromancer']);
echo '== After delete' . PHP_EOL;
displayBooks($pdo->query('SELECT * FROM books ORDER BY year'));
