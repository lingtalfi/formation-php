# 02 — Exercises

All exercises are command-line scripts: `php file.php`.

## 2.1 — FizzBuzz, PHP edition

Print the numbers from 1 to 30. For multiples of 3 print `Fizz`, for multiples of 5 print `Buzz`, for multiples of both print `FizzBuzz`.

Write it once with `if/elseif`, then a second version using `match(true)`.

## 2.2 — Grades

Start from this array:

```php
$grades = [
    'Alice' => 15,
    'Bob'   => 9,
    'Chloe' => 18,
    'David' => 12,
    'Emma'  => 7,
];
```

1. Display each student with their grade: `Alice: 15/20`.
2. Compute and display the class average (2 decimals).
3. Display the best student and their grade.
4. Display the list of students who passed (grade >= 10), separated by commas. Use `array_filter` and `implode`.
5. Display the students sorted from best to worst grade, keeping names attached to grades.

## 2.3 — Functions

Write these functions with full type declarations, then call them to test:

1. `isPalindrome(string $word): bool` — `"Kayak"` → `true`. Ignore case.
2. `initials(string $fullName): string` — `"jean-paul sartre"` → `"J.S."`. Hint: `explode`, `strtoupper`, `substr`. (Bonus: `"J.P.S."` by also splitting on `-`.)
3. `applyDiscount(float $price, float $percent = 10): float` — returns the discounted price, rounded to 2 decimals. Call it once with named arguments.

## 2.4 — Shopping cart

```php
$cart = [
    ['product' => 'Keyboard', 'price' => 49.90, 'qty' => 1],
    ['product' => 'Mouse',    'price' => 19.50, 'qty' => 2],
    ['product' => 'Screen',   'price' => 189.00, 'qty' => 1],
];
```

1. Display one line per product: name, quantity, line total.
2. Display the cart total.
3. Using `array_map`, build an array containing only the product names.
4. Write a function `cartTotal(array $cart): float` and use it.

## 2.5 — Dates

1. Display today's date as `Friday 16 October 2026`.
2. Display the number of days until next 1st of January.
3. Given `$birthday = '1990-05-14'`, display the age in years (use `DateTime` and `diff`).
