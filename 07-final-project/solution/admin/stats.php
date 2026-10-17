<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Auth;
use App\Database;
use App\StatsRepository;

Auth::requireAdmin();

const DAYS = 7;

$stats = new StatsRepository(Database::connection());
$summary  = $stats->summary(DAYS);
$perDay   = $stats->perDay(DAYS);
$browsers = $stats->top('browser', DAYS);
$systems  = $stats->top('os', DAYS);
$searches = $stats->top('search', DAYS);
$latest   = $stats->latest(20);

/** A small table of label / total. */
function top_table(string $title, array $rows): string
{
    $html = '<div><h3>' . e($title) . '</h3><table>';
    foreach ($rows as $row) {
        $html .= '<tr><td>' . e($row['label']) . '</td><td class="num">' . (int) $row['total'] . '</td></tr>';
    }
    if (!$rows) {
        $html .= '<tr><td class="muted">No data yet</td></tr>';
    }
    return $html . '</table></div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin — Statistics</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>Statistics</h1>
        <a href="index.php">Back to books</a>
    </header>

    <p class="muted">Last <?= DAYS ?> days, public pages only.</p>

    <div class="cards">
        <div><strong><?= (int) $summary['views'] ?></strong><br>page views</div>
        <div><strong><?= (int) $summary['visitors'] ?></strong><br>visitors</div>
        <div><strong><?= (int) $summary['avg_ms'] ?> ms</strong><br>average page time</div>
    </div>

    <h2>Per day</h2>
    <table>
        <tr><th>Day</th><th class="num">Views</th><th class="num">Visitors</th></tr>
        <?php foreach ($perDay as $row): ?>
            <tr>
                <td><?= e((new DateTime($row['day']))->format('D d/m')) ?></td>
                <td class="num"><?= (int) $row['views'] ?></td>
                <td class="num"><?= (int) $row['visitors'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="columns">
        <?= top_table('Browsers', $browsers) ?>
        <?= top_table('Systems', $systems) ?>
        <?= top_table('Top searches', $searches) ?>
    </div>

    <h2>Latest visits</h2>
    <table class="small">
        <tr><th>When</th><th>IP</th><th>Page</th><th>Status</th><th class="num">ms</th><th>Browser / OS</th><th>Lang</th></tr>
        <?php foreach ($latest as $v): ?>
            <tr>
                <td><?= e((new DateTime($v['visited_at']))->format('d/m H:i:s')) ?></td>
                <td><?= e($v['ip']) ?></td>
                <td>
                    <?= e($v['method']) ?> <?= e($v['path']) ?>
                    <?php if ($v['search']): ?><span class="muted">“<?= e($v['search']) ?>”</span><?php endif; ?>
                </td>
                <td><?= (int) $v['status'] ?></td>
                <td class="num"><?= (int) $v['duration_ms'] ?></td>
                <td title="<?= e($v['user_agent']) ?>"><?= e($v['browser']) ?> / <?= e($v['os']) ?></td>
                <td><?= e($v['language']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
