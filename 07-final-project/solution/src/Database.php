<?php
declare(strict_types=1);

namespace App;

use PDO;

class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = new PDO('sqlite:' . __DIR__ . '/../bookshop.db', null, null, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            self::install(self::$pdo);
        }
        return self::$pdo;
    }

    /** Creates the tables and some books on first run. Delete bookshop.db to start over. */
    private static function install(PDO $pdo): void
    {
        $pdo->exec('CREATE TABLE IF NOT EXISTS books (
            id         INTEGER PRIMARY KEY AUTOINCREMENT,
            title      TEXT NOT NULL,
            author     TEXT NOT NULL,
            year       INTEGER,
            price      REAL NOT NULL DEFAULT 0,
            created_at TEXT NOT NULL
        )');

        // One row per page view, filled by Tracker
        $pdo->exec('CREATE TABLE IF NOT EXISTS visits (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            visited_at  TEXT NOT NULL,
            ip          TEXT NOT NULL,
            method      TEXT NOT NULL,
            path        TEXT NOT NULL,
            search      TEXT,
            status      INTEGER NOT NULL,
            duration_ms INTEGER NOT NULL,
            referer     TEXT,
            user_agent  TEXT,
            browser     TEXT,
            os          TEXT,
            language    TEXT,
            visitor_id  TEXT
        )');
        $pdo->exec('CREATE INDEX IF NOT EXISTS idx_visits_date ON visits (visited_at)');

        if ((int) $pdo->query('SELECT COUNT(*) FROM books')->fetchColumn() > 0) {
            return;
        }

        $books = [
            ['1984', 'George Orwell', 1949, 9.90],
            ['Animal Farm', 'George Orwell', 1945, 7.50],
            ['Brave New World', 'Aldous Huxley', 1932, 9.20],
            ['Fahrenheit 451', 'Ray Bradbury', 1953, 8.40],
            ['The Martian Chronicles', 'Ray Bradbury', 1950, 8.90],
            ['Dune', 'Frank Herbert', 1965, 12.90],
            ['Foundation', 'Isaac Asimov', 1951, 10.50],
            ['I, Robot', 'Isaac Asimov', 1950, 9.00],
            ['The Caves of Steel', 'Isaac Asimov', 1954, 9.50],
            ['Neuromancer', 'William Gibson', 1984, 11.00],
            ['Snow Crash', 'Neal Stephenson', 1992, 12.50],
            ['Hyperion', 'Dan Simmons', 1989, 11.90],
            ['The Left Hand of Darkness', 'Ursula K. Le Guin', 1969, 10.90],
            ['A Wizard of Earthsea', 'Ursula K. Le Guin', 1968, 8.90],
            ['The Dispossessed', 'Ursula K. Le Guin', 1974, 11.20],
            ['The Hobbit', 'J.R.R. Tolkien', 1937, 10.00],
            ['The Fellowship of the Ring', 'J.R.R. Tolkien', 1954, 13.90],
            ['Do Androids Dream of Electric Sheep?', 'Philip K. Dick', 1968, 9.60],
            ['Ubik', 'Philip K. Dick', 1969, 9.30],
            ['The Hitchhiker\'s Guide to the Galaxy', 'Douglas Adams', 1979, 8.50],
            ['Solaris', 'Stanisław Lem', 1961, 10.40],
            ['The Three-Body Problem', 'Liu Cixin', 2008, 13.50],
            ['Ender\'s Game', 'Orson Scott Card', 1985, 9.80],
            ['The Handmaid\'s Tale', 'Margaret Atwood', 1985, 10.20],
            ['Never Let Me Go', 'Kazuo Ishiguro', 2005, 11.50],
            ['The Road', 'Cormac McCarthy', 2006, 10.80],
            ['Station Eleven', 'Emily St. John Mandel', 2014, 12.00],
            ['Project Hail Mary', 'Andy Weir', 2021, 14.90],
            ['The Martian', 'Andy Weir', 2011, 11.40],
            ['Klara and the Sun', 'Kazuo Ishiguro', 2021, 13.20],
        ];

        $stmt = $pdo->prepare(
            'INSERT INTO books (title, author, year, price, created_at) VALUES (?, ?, ?, ?, ?)'
        );
        foreach ($books as [$title, $author, $year, $price]) {
            $stmt->execute([$title, $author, $year, $price, date('Y-m-d H:i:s')]);
        }
    }
}
