<?php
declare(strict_types=1);

// Usage: php library.php            -> full report
//        php library.php orwell     -> books by authors matching "orwell"

$pdo = new PDO('mysql:host=127.0.0.1;dbname=training;charset=utf8mb4', 'training', 'training', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
]);

// Optional search argument
if (isset($argv[1])) {
    $stmt = $pdo->prepare(
        'SELECT b.title, b.year, a.name AS author
         FROM books b
         JOIN authors a ON a.id = b.author_id
         WHERE a.name LIKE ?
         ORDER BY b.year'
    );
    $stmt->execute(['%' . $argv[1] . '%']);
    $books = $stmt->fetchAll();

    if (!$books) {
        echo "No author matching \"{$argv[1]}\"." . PHP_EOL;
    }
    foreach ($books as $b) {
        echo "{$b['year']}  {$b['title']} ({$b['author']})" . PHP_EOL;
    }
    exit;
}

// 1. All books with their author
echo '== Books' . PHP_EOL;
$sql = 'SELECT b.title, b.year, b.price, a.name AS author
        FROM books b
        JOIN authors a ON a.id = b.author_id
        ORDER BY a.name, b.year';
foreach ($pdo->query($sql) as $b) {
    printf("%-20s %4d  %-28s %6.2f €\n", $b['author'], $b['year'], $b['title'], $b['price']);
}

// 2. Stats per author
echo PHP_EOL . '== Per author' . PHP_EOL;
$sql = 'SELECT a.name, COUNT(b.id) AS nb_books, AVG(b.price) AS avg_price
        FROM authors a
        LEFT JOIN books b ON b.author_id = a.id
        GROUP BY a.id, a.name
        ORDER BY a.name';
foreach ($pdo->query($sql) as $row) {
    printf("%-20s %d book(s), average %.2f €\n", $row['name'], $row['nb_books'], $row['avg_price'] ?? 0);
}
