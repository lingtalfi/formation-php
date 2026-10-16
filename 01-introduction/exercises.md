# 01 — Exercises

Create your files in a working folder of your own (for example `my-work/01/`).

## 1.1 — Explore and change your configuration

1. Run `php --ini` and find the path of the loaded `php.ini`. If it says `(none)`, create it from `php.ini-development` (ask the trainer if you can't find it).
2. Run `php -m` and check that `pdo_sqlite` is in the list.
3. Create `info.php` containing `<?php phpinfo();`, start `php -S localhost:8000` in that folder and open <http://localhost:8000/info.php>. Note the values of `display_errors`, `upload_max_filesize` and `date.timezone`.
4. Open your `php.ini` in your editor and set:

   ```ini
   date.timezone = Europe/Paris
   upload_max_filesize = 10M
   post_max_size = 12M
   ```

   Restart `php -S` (`Ctrl+C`, then start it again) and reload `info.php`: the new values must appear.
5. Create `ini.php`:

   ```php
   <?php
   var_dump(ini_get('memory_limit'));
   var_dump(ini_set('memory_limit', '256M'));
   var_dump(ini_get('memory_limit'));

   var_dump(ini_set('upload_max_filesize', '50M'));
   var_dump(ini_get('upload_max_filesize'));
   ```

   Run it with `php ini.php`. Why does the first `ini_set` work and not the second?
6. Run `php -d memory_limit=64M ini.php`. What changed in the first line, and why?

*No solution file: the answers are in the lesson. Compare with the trainer.*

## 1.2 — Business card page

Create `card.php`, a web page that displays a business card.

- Store your first name, last name, job and year of birth in variables.
- Store the current year with `date('Y')`.
- Display: the full name in an `<h1>`, the job, and the age (computed).
- Use both `<?php echo ... ?>` and the short form `<?= ... ?>` at least once.

Run it with `php -S localhost:8000` and open <http://localhost:8000/card.php>.

## 1.3 — Type detective

Create `types.php`, a command-line script (`php types.php`).

1. Predict, **on paper first**, the result of each line, then run with `var_dump()` to check:

   ```php
   "10" + 5
   "10" . 5
   "3 apples" + 2      // PHP 8 gives a warning here: read it
   7 / 2
   intdiv(7, 2)
   7 % 2
   (int) "12.9"
   (bool) "0"
   (bool) "false"
   0 == ""
   0 === ""
   null ?? "default"
   ```

2. Define a constant `VAT_RATE` (0.2) and a variable `$priceExclTax` (49.90). Display the price including tax, rounded to 2 decimals with `round()`.

3. Display your result with `number_format($value, 2, ',', ' ')` to get the French format (`59,88`).
