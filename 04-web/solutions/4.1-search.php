<?php
$languages = ['PHP', 'Python', 'Java', 'JavaScript', 'Go', 'Rust', 'Ruby'];

$q = trim($_GET['q'] ?? '');
$results = [];

if ($q !== '') {
    $results = array_filter(
        $languages,
        fn($lang) => str_contains(strtolower($lang), strtolower($q))
    );
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Search</title></head>
<body>
    <form method="get">
        <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search a language">
        <button type="submit">Search</button>
    </form>

    <?php if ($q !== ''): ?>
        <p>You searched for: <strong><?= e($q) ?></strong></p>
        <?php if ($results): ?>
            <ul>
                <?php foreach ($results as $lang): ?>
                    <li><?= e($lang) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No result.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
