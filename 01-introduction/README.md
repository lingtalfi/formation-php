# 01 — Introduction to PHP

## What PHP is

PHP is a language that runs **on the server**. The browser asks for a page, PHP runs, produces HTML, and only that HTML is sent back. The visitor never sees your PHP code.

```
Browser  --request-->  Web server + PHP  --runs page.php-->  HTML
Browser  <--HTML-----  Web server
```

A few facts worth knowing:

- Created in 1994 by Rasmus Lerdorf ("Personal Home Page", now "PHP: Hypertext Preprocessor").
- Powers a large share of the web: WordPress, Drupal, Magento, Laravel and Symfony apps.
- PHP 7 (2015) made it about twice as fast. PHP 8 (2020+) added modern features: named arguments, `match`, enums, readonly properties, union types.
- Dynamically typed, like Python, with optional type declarations, a bit like Java.

## Configuring the engine

PHP reads its settings from a file called `php.ini`.

```bash
php --ini        # shows which php.ini is loaded
php -m           # lists loaded extensions
php -i           # all settings (long!)
```

In a web page, `<?php phpinfo(); ?>` shows the same information as a page. **Never leave it on a public site**: it reveals your configuration.

Settings you'll meet most often:

| Setting | Meaning | Dev value |
|---|---|---|
| `display_errors` | Show errors in the page | `On` (never in production) |
| `error_reporting` | Which errors are reported | `E_ALL` |
| `upload_max_filesize` | Max size of an uploaded file | `2M` by default |
| `memory_limit` | Max memory per script | `128M` by default |
| `date.timezone` | Default timezone | `Europe/Paris` |

You can also change some settings from code: `ini_set('display_errors', '1');`

## PHP inside HTML

A `.php` file is HTML by default. PHP code goes between `<?php` and `?>`.

```php
<!DOCTYPE html>
<html>
<body>
    <h1><?php echo "Hello"; ?></h1>
    <p>Today is <?= date('l') ?></p>   <!-- <?= ... ?> is a shortcut for echo -->
</body>
</html>
```

A file containing **only** PHP (classes, scripts) starts with `<?php` and should **not** end with `?>`: this avoids sending stray whitespace by accident.

Instructions end with `;`. Comments: `// one line`, `# one line`, `/* several lines */`.

## Variables

Variables start with `$`. No declaration, no type needed.

```php
$name = "Alice";
$age = 32;
$price = 19.99;
$isAdmin = false;
$nothing = null;
```

The four scalar types: `string`, `int`, `float`, `bool`. Plus `array`, `object` and `null`.

```php
var_dump($age);       // int(32)  -> type AND value, your best debugging friend
echo gettype($price); // double (historical name for float)
```

### Strings

```php
$first = 'Alice';
echo 'Hello $first';      // Hello $first   (single quotes: no interpolation)
echo "Hello $first";      // Hello Alice    (double quotes: variables replaced)
echo "Hello {$first}!";   // braces: safer when text follows
echo 'Hello ' . $first;   // . is the concatenation operator (not +)
```

### Constants

```php
const VAT_RATE = 0.2;           // preferred, at top level or in classes
define('SITE_NAME', 'My shop'); // older style, works anywhere at runtime

echo VAT_RATE;                  // no $
```

Built-in constants: `PHP_VERSION`, `PHP_EOL` (line break), `PHP_INT_MAX`.

## Type juggling and casting

PHP converts types automatically when needed. That's handy and sometimes surprising.

```php
var_dump("5" + 3);      // int(8)    the string is converted to a number
var_dump("5" . 3);      // string(2) "53"
var_dump(10 / 4);       // float(2.5)
var_dump(intdiv(10, 4)); // int(2)
```

Explicit conversion (casting):

```php
$n = (int) "42abc";   // 42
$f = (float) "3.14";  // 3.14
$s = (string) 12;     // "12"
$b = (bool) "";       // false
```

Values that are `false` when converted to bool: `false`, `0`, `0.0`, `""`, `"0"`, `[]`, `null`. Everything else is `true`.

### `==` vs `===`

```php
var_dump(0 == "0");     // true  : values compared after conversion
var_dump(0 === "0");    // false : value AND type must match
var_dump(null == false); // true
```

**Rule: use `===` and `!==` by default.** Use `==` only when you mean it.

### Strict types

Add this at the very top of a file to forbid automatic conversion when calling typed functions:

```php
<?php
declare(strict_types=1);
```

We'll see typed functions in module 02.

---

Now do the [exercises](exercises.md).
