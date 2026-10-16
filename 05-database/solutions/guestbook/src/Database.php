<?php
declare(strict_types=1);

namespace App;

use PDO;

class Database
{
    private static ?PDO $pdo = null;

    // One connection for the whole request, created on first use
    public static function connection(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = new PDO('sqlite:' . __DIR__ . '/../guestbook.db', null, null, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            self::$pdo->exec('CREATE TABLE IF NOT EXISTS messages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                author TEXT NOT NULL,
                content TEXT NOT NULL,
                created_at TEXT NOT NULL
            )');
        }
        return self::$pdo;
    }
}
