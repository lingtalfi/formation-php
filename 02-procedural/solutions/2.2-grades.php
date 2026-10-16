<?php
$grades = [
    'Alice' => 15,
    'Bob'   => 9,
    'Chloe' => 18,
    'David' => 12,
    'Emma'  => 7,
];

// 1. Each student
foreach ($grades as $name => $grade) {
    echo "$name: $grade/20" . PHP_EOL;
}

// 2. Average
$average = array_sum($grades) / count($grades);
echo 'Average: ' . number_format($average, 2) . PHP_EOL;

// 3. Best student
$best = max($grades);
$bestName = array_search($best, $grades);
echo "Best: $bestName ($best/20)" . PHP_EOL;

// 4. Passed students: array_filter keeps the keys, so we take them with array_keys
$passed = array_filter($grades, fn($g) => $g >= 10);
echo 'Passed: ' . implode(', ', array_keys($passed)) . PHP_EOL;

// 5. Sorted best to worst, keys kept: arsort (sort() would lose the names!)
$sorted = $grades;   // copy, so $grades stays untouched
arsort($sorted);
foreach ($sorted as $name => $grade) {
    echo "$name: $grade" . PHP_EOL;
}
