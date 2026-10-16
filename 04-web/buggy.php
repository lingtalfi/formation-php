<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

$users = [
    ['name' => 'Alice', 'birth' => '1990-05-14'],
    ['name' => 'Bob',   'birth' => '1985-11-02'],
    ['name' => 'Chloe', 'birth' => '2001-01-30'],
]

function age(string $birth): int
{
    $diff = (new DateTime($birth))->diff(new DateTime('today'));
    return $diff->days;
}

$total = 0;
foreach ($users as $user) {
    $total =+ age($user['birth']);
}
$average = $total / count($user);
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Users</title></head>
<body>
    <h1>Users</h1>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><?= htmlspecialchars($user['Name']) ?> — <?= age($user['birth']) ?> years old</li>
        <?php endforeach; ?>
    </ul>
    <p>Average age: <?= round($average, 1) ?></p>
</body>
</html>
