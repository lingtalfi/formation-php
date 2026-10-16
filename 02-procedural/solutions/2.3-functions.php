<?php
declare(strict_types=1);

function isPalindrome(string $word): bool
{
    $word = mb_strtolower($word);
    return $word === strrev($word);   // strrev works on bytes: fine for non-accented words
}

function initials(string $fullName): string
{
    $result = '';
    // Bonus: split on spaces AND dashes
    $parts = preg_split('/[\s-]+/', trim($fullName));
    foreach ($parts as $part) {
        $result .= strtoupper(substr($part, 0, 1)) . '.';
    }
    return $result;
}

function applyDiscount(float $price, float $percent = 10): float
{
    return round($price * (1 - $percent / 100), 2);
}

var_dump(isPalindrome('Kayak'));          // true
var_dump(isPalindrome('PHP'));            // true
var_dump(isPalindrome('Python'));         // false
echo initials('jean-paul sartre') . PHP_EOL;   // J.P.S.
echo applyDiscount(80) . PHP_EOL;              // 72
echo applyDiscount(percent: 25, price: 80) . PHP_EOL; // 60
