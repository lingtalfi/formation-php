# 06 — Exercises

## 6.1 — Start and explore the server

1. Start MySQL (Docker or native, see the lesson).
2. Connect with the command-line client and run `SELECT VERSION();` and `SHOW DATABASES;`.
3. Open Adminer (<http://localhost:8080>) or another GUI and log into the `training` database.
4. Check that PHP has the driver: `php -m` must list `pdo_mysql`.
5. Write `connect.php` that connects with PDO and displays `Connected to MySQL x.y.z` using `SELECT VERSION()`.
6. Break it on purpose, one at a time, and read the error: wrong password, wrong database name, `localhost` instead of `127.0.0.1` (macOS/Linux with Docker), server stopped.

## 6.2 — Library with two tables

1. Write `schema.sql` creating two tables:
   - `authors` (id, name)
   - `books` (id, title, year, price as `DECIMAL(6,2)`, author_id with a foreign key to `authors`)

   Start the file with `DROP TABLE IF EXISTS books, authors;` so you can reload it as often as you want. Insert at least 3 authors and 6 books.
2. Load it into MySQL (command line or Adminer's *Import*).
3. Write `library.php` (command line) that:
   - lists every book with its author's name, sorted by author then year (`JOIN`)
   - displays the number of books and the average price per author (`GROUP BY`, `COUNT`, `AVG`)
   - asks for an author name as command-line argument (`php library.php Orwell`) and lists only their books, with a prepared statement and `LIKE`. Hint: `$argv[1]`.
4. Try inserting a book with an `author_id` that doesn't exist. What happens, and why is it a good thing?

## 6.3 — Move the guestbook to MySQL

Take your guestbook from exercise 5.3 (or the provided solution) and make it run on MySQL.

1. Write `schema.sql` for the `messages` table with proper MySQL types (`VARCHAR`, `TEXT`, `DATETIME DEFAULT CURRENT_TIMESTAMP`).
2. Create `config.php` (credentials) and `config.example.php` (same keys, fake values). Add `config.php` to a `.gitignore`.
3. Change `Database.php` to read the config and connect to MySQL. Remove the `CREATE TABLE` from the PHP code: the schema now lives in `schema.sql`.
4. Run the app. **Nothing else should need to change**: `MessageRepository`, `index.php` and `admin.php` stay as they are. That's the point of PDO and of keeping all SQL in one class.

Bonus 1: the guestbook paginates with `bindValue(..., PDO::PARAM_INT)` in `MessageRepository::findPage()`. Replace it with a plain `$stmt->execute([$limit, $offset])` and try with `ATTR_EMULATE_PREPARES` set to `true`, then `false`. What happens, and why? (Answer in the *Connecting from PHP* section of the lesson.)

Bonus 2: move the bookshop app (exercise 5.4) to MySQL the same way: `config.php`, a `schema.sql` with proper MySQL types (`DECIMAL(6,2)` for the price), and a new `Database.php`. The repository, the validator and all the pages should stay unchanged.
