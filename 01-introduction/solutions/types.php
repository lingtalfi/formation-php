<?php
// Part 1: predictions
var_dump("10" + 5);          // int(15)
var_dump("10" . 5);          // string(3) "105"
var_dump("3 apples" + 2);    // int(5) + Warning: A non-numeric value... (leading-numeric string)
var_dump(7 / 2);             // float(3.5)
var_dump(intdiv(7, 2));      // int(3)
var_dump(7 % 2);             // int(1)
var_dump((int) "12.9");      // int(12)  (truncated, not rounded)
var_dump((bool) "0");        // bool(false)  "0" is one of the falsy values
var_dump((bool) "false");    // bool(true)   any non-empty string except "0"
var_dump(0 == "");           // bool(false)  since PHP 8 (was true in PHP 7!)
var_dump(0 === "");          // bool(false)
var_dump(null ?? "default"); // string(7) "default"

// Part 2 and 3: price with tax
const VAT_RATE = 0.2;
$priceExclTax = 49.90;
$priceInclTax = round($priceExclTax * (1 + VAT_RATE), 2);

echo $priceInclTax . PHP_EOL;                                  // 59.88
echo number_format($priceInclTax, 2, ',', ' ') . ' €' . PHP_EOL; // 59,88 €
