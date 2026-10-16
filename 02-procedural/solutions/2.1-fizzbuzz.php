<?php
// Version 1: if / elseif
for ($i = 1; $i <= 30; $i++) {
    if ($i % 15 === 0) {
        echo 'FizzBuzz';
    } elseif ($i % 3 === 0) {
        echo 'Fizz';
    } elseif ($i % 5 === 0) {
        echo 'Buzz';
    } else {
        echo $i;
    }
    echo PHP_EOL;
}

echo '---' . PHP_EOL;

// Version 2: match(true) — the first arm whose condition is true wins
for ($i = 1; $i <= 30; $i++) {
    echo match (true) {
        $i % 15 === 0 => 'FizzBuzz',
        $i % 3 === 0  => 'Fizz',
        $i % 5 === 0  => 'Buzz',
        default       => $i,
    } . PHP_EOL;
}
