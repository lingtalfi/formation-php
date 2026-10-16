<?php
declare(strict_types=1);

namespace App;

use PDO;

class Database
{
    private static ?PDO $pdo = null;

    // One connection for the whole request, created on first use.
    // Only this file changed compared to the SQLite version (module 05).
    public static function connection(): PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../config.php';

            self::$pdo = new PDO($config['dsn'], $config['user'], $config['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            // No CREATE TABLE here anymore: the schema lives in schema.sql
        }
        return self::$pdo;
    }
}
