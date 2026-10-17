<?php
declare(strict_types=1);

namespace App;

use PDO;

class MessageRepository
{
    public function __construct(private PDO $pdo) {}

    public function findAll(): array
    {
        return $this->pdo
            ->query('SELECT * FROM messages ORDER BY created_at DESC, id DESC')
            ->fetchAll();
    }

    /**
     * One page of messages, newest first.
     * LIMIT and OFFSET are bound as integers (PDO::PARAM_INT): with MySQL, binding them
     * as strings (what execute([...]) does) can produce LIMIT '5', which is invalid.
     */
    public function findPage(int $limit, int $offset): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM messages ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function add(string $author, string $content): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO messages (author, content, created_at) VALUES (:author, :content, :created_at)'
        );
        $stmt->execute([
            'author'     => $author,
            'content'    => $content,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM messages WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() === 1;
    }

    public function count(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM messages')->fetchColumn();
    }
}
