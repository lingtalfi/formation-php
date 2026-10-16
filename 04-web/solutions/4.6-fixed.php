<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

$users = [
    ['name' => 'Alice', 'birth' => '1990-05-14'],
    ['name' => 'Bob',   'birth' => '1985-11-02'],
    ['name' => 'Chloe', 'birth' => '2001-01-30'],
];  // BUG 1: missing semicolon -> Parse error (reported on the NEXT line, "function")

function age(string $birth): int
{
    $diff = (new DateTime($birth))->diff(new DateTime('today'));
    return $diff->y;   // BUG 2: ->days is the total number of days, ->y is the years
}

$total = 0;
foreach ($users as $user) {
    $total += age($user['birth']);   // BUG 3: "=+" is "= +age()", it overwrites instead of adding
}
$average = $total / count($users);   // BUG 4: count($user) counted the fields of the last user (2)
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Users</title></head>
<body>
    <h1>Users</h1>
    <ul>
        <?php foreach ($users as $user): ?>
            <!-- BUG 5: array keys are case-sensitive: 'name', not 'Name' -->
            <li><?= htmlspecialchars($user['name']) ?> — <?= age($user['birth']) ?> years old</li>
        <?php endforeach; ?>
    </ul>
    <p>Average age: <?= round($average, 1) ?></p>
</body>
</html>
