<?php
// 1. Today
echo date('l d F Y') . PHP_EOL;

// 2. Days until next 1st of January
$today = new DateTime('today');
$newYear = new DateTime('first day of january next year');
echo $today->diff($newYear)->days . ' days until New Year' . PHP_EOL;

// 3. Age
$birthday = new DateTime('1990-05-14');
echo 'Age: ' . $birthday->diff($today)->y . PHP_EOL;
