<?php
declare(strict_types=1);

namespace App;

use PDO;

/** All the SQL about books lives here. */
class BookRepository
{
    /**
     * Sortable columns: URL value => SQL column.
     * ORDER BY can't use a placeholder, so the column name is inserted in the SQL string.
     * This whitelist is what makes it safe: only these exact values can ever get there.
     */
    public const SORTABLE = [
        'title'  => 'title',
        'author' => 'author',
        'year'   => 'year',
        'price'  => 'price',
    ];

    public function __construct(private PDO $pdo) {}

    /** Number of books matching the search (needed to compute the number of pages). */
    public function count(string $search = ''): int
    {
        [$where, $params] = $this->searchCondition($search);
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM books $where");
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /** One page of books matching the search, sorted. */
    public function search(string $search, string $sort, string $direction, int $limit, int $offset): array
    {
        [$where, $params] = $this->searchCondition($search);

        $column = self::SORTABLE[$sort] ?? 'title';
        $direction = $direction === 'desc' ? 'DESC' : 'ASC';

        // ", id" as a second sort key: books with the same year stay in a stable order between pages
        $sql = "SELECT * FROM books $where ORDER BY $column $direction, id ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $name => $value) {
            $stmt->bindValue($name, $value);
        }
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM books WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;   // fetch() returns false when not found
    }

    public function create(array $book): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO books (title, author, year, price, created_at)
             VALUES (:title, :author, :year, :price, :created_at)'
        );
        $stmt->execute([...$book, 'created_at' => date('Y-m-d H:i:s')]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $book): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE books SET title = :title, author = :author, year = :year, price = :price WHERE id = :id'
        );
        $stmt->execute([...$book, 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM books WHERE id = ?');
        $stmt->execute([$id]);
    }

    /**
     * WHERE clause + parameters for a text search on title or author.
     * Two different placeholder names: reusing the same name twice isn't supported
     * by every driver (MySQL with real prepared statements refuses it).
     */
    private function searchCondition(string $search): array
    {
        if ($search === '') {
            return ['', []];
        }
        return [
            'WHERE title LIKE :q1 OR author LIKE :q2',
            ['q1' => "%$search%", 'q2' => "%$search%"],
        ];
    }
}
