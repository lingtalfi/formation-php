<?php
declare(strict_types=1);

$dsn = 'mysql:host=127.0.0.1;port=3306;dbname=training;charset=utf8mb4';

try {
    $pdo = new PDO($dsn, 'training', 'training', [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    echo 'Connected to MySQL ' . $pdo->query('SELECT VERSION()')->fetchColumn() . PHP_EOL;
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}
