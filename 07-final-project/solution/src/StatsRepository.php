<?php
declare(strict_types=1);

namespace App;

use PDO;

/** Read-only queries on the visits table, for the admin stats page. */
class StatsRepository
{
    public function __construct(private PDO $pdo) {}

    /** Page views, distinct visitors, average duration over the last $days days. */
    public function summary(int $days): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*)                  AS views,
                    COUNT(DISTINCT visitor_id) AS visitors,
                    ROUND(AVG(duration_ms))   AS avg_ms
             FROM visits
             WHERE visited_at >= :since'
        );
        $stmt->execute(['since' => $this->since($days)]);
        return $stmt->fetch();
    }

    /** One row per day: date, views, visitors. */
    public function perDay(int $days): array
    {
        // substr(visited_at, 1, 10) = the "YYYY-MM-DD" part; with MySQL you'd write DATE(visited_at)
        $stmt = $this->pdo->prepare(
            'SELECT substr(visited_at, 1, 10)    AS day,
                    COUNT(*)                   AS views,
                    COUNT(DISTINCT visitor_id) AS visitors
             FROM visits
             WHERE visited_at >= :since
             GROUP BY day
             ORDER BY day DESC'
        );
        $stmt->execute(['since' => $this->since($days)]);
        return $stmt->fetchAll();
    }

    /**
     * Most frequent values of one column: browser, os, language, search...
     * The column name is checked against a whitelist (it can't be a placeholder).
     */
    public function top(string $column, int $days, int $limit = 5): array
    {
        if (!in_array($column, ['browser', 'os', 'language', 'search', 'path', 'referer'], true)) {
            throw new \InvalidArgumentException("Column not allowed: $column");
        }
        $stmt = $this->pdo->prepare(
            "SELECT $column AS label, COUNT(*) AS total
             FROM visits
             WHERE visited_at >= :since AND $column IS NOT NULL
             GROUP BY $column
             ORDER BY total DESC
             LIMIT :limit"
        );
        $stmt->bindValue('since', $this->since($days));
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function latest(int $limit = 20): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM visits ORDER BY id DESC LIMIT :limit');
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function since(int $days): string
    {
        return date('Y-m-d 00:00:00', strtotime('-' . ($days - 1) . ' days'));
    }
}
