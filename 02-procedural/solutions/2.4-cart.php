<?php
declare(strict_types=1);

$cart = [
    ['product' => 'Keyboard', 'price' => 49.90, 'qty' => 1],
    ['product' => 'Mouse',    'price' => 19.50, 'qty' => 2],
    ['product' => 'Screen',   'price' => 189.00, 'qty' => 1],
];

function cartTotal(array $cart): float
{
    $total = 0.0;
    foreach ($cart as $line) {
        $total += $line['price'] * $line['qty'];
    }
    return $total;
}

// 1. One line per product
foreach ($cart as $line) {
    printf(
        "%-10s x%d  %8.2f €\n",
        $line['product'],
        $line['qty'],
        $line['price'] * $line['qty']
    );
}

// 2 + 4. Total
printf("Total: %.2f €\n", cartTotal($cart));

// 3. Product names only
$names = array_map(fn($line) => $line['product'], $cart);
echo implode(', ', $names) . PHP_EOL;
// Shortcut worth knowing: array_column($cart, 'product')
