<?php
// Checks that your PHP installation is ready for the course.
// Run: php 00-setup/check.php

$ok = true;

function report(bool $condition, string $label, string $fix = ''): bool
{
    echo ($condition ? '[OK]   ' : '[FAIL] ') . $label . PHP_EOL;
    if (!$condition && $fix !== '') {
        echo '       -> ' . $fix . PHP_EOL;
    }
    return $condition;
}

$ok = report(
    version_compare(PHP_VERSION, '8.2.0', '>='),
    'PHP version ' . PHP_VERSION . ' (8.2+ required)',
    'Install a newer PHP (see 00-setup/README.md).'
) && $ok;

$ok = report(
    extension_loaded('pdo_sqlite'),
    'Extension pdo_sqlite (database module)',
    'Windows: enable extension=pdo_sqlite in php.ini. Linux: install php-sqlite3.'
) && $ok;

$ok = report(
    extension_loaded('mbstring'),
    'Extension mbstring (multibyte strings)',
    'Windows: enable extension=mbstring in php.ini. Linux: install php-mbstring.'
) && $ok;

$ok = report(
    extension_loaded('fileinfo'),
    'Extension fileinfo (file upload checks)',
    'Windows: enable extension=fileinfo in php.ini.'
) && $ok;

if (extension_loaded('pdo_sqlite')) {
    try {
        $pdo = new PDO('sqlite::memory:');
        $value = $pdo->query('SELECT 1 + 1')->fetchColumn();
        $ok = report($value == 2, 'SQLite query works') && $ok;
    } catch (Throwable $e) {
        $ok = report(false, 'SQLite query works', $e->getMessage()) && $ok;
    }
}

echo PHP_EOL . ($ok ? 'All good, you are ready!' : 'Some checks failed: fix them or bring the messages to the training.') . PHP_EOL;
