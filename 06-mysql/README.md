# 06 — MySQL (going to production)

*Optional module: second day or self-study.*

Module 05 used SQLite: perfect to learn, since it's one file and nothing to install. Production PHP sites almost always run on a **database server** instead, and in the PHP world that's mostly **MySQL** or its fork **MariaDB**. They're nearly interchangeable for everything in this course. WordPress, WooCommerce, Drupal, Magento and most shared hosting offers rely on them.

> **PostgreSQL** is the other big one, popular in Symfony and Laravel projects. With PDO, switching is mostly the connection string: `pgsql:host=127.0.0.1;dbname=training`.

The good news: **your PDO code doesn't change**. Same `prepare`, `execute`, `fetch`, transactions. What changes is everything around it: installing a server, users and passwords, column types, configuration.

## SQLite vs MySQL

| | SQLite | MySQL / MariaDB |
|---|---|---|
| What it is | a library writing to one file | a server program you connect to over the network |
| Users | none: file permissions | users, passwords, permissions per database |
| Concurrent writes | limited | built for many simultaneous users |
| Typical use | tests, prototypes, mobile apps, small sites | production web apps |
| PDO extension | `pdo_sqlite` | `pdo_mysql` |

## Installing a MySQL server

### Option A — Docker (recommended: identical on every OS)

Install [Docker Desktop](https://www.docker.com/products/docker-desktop/) (Windows, macOS) or Docker Engine (Linux). Then, in this folder:

```bash
docker compose up -d
```

This starts two things, defined in [`docker-compose.yml`](docker-compose.yml):

- a MySQL 8.4 server on port `3306`, with a database `training` and a user `training` / password `training`
- **Adminer**, a web interface to browse the database: <http://localhost:8080> (System: *MySQL*, Server: `db`, user/password: `training`)

```bash
docker compose ps        # is it running?
docker compose logs db   # server logs (wait for "ready for connections")
docker compose down      # stop (data is kept in a volume)
```

> Port 3306 already used (an existing MySQL on your machine)? Change `"3306:3306"` to `"3307:3306"` in the compose file and use port 3307 in your DSN.

### Option B — native install

| OS | Install | Start |
|---|---|---|
| macOS | `brew install mysql` | `brew services start mysql` |
| Windows | [MySQL Community Installer](https://dev.mysql.com/downloads/installer/) (choose "Server only") | runs as a Windows service |
| Linux | `sudo apt install mariadb-server` | starts automatically |

Then create the database and user yourself (see *Users and permissions* below).

On Linux, MariaDB's `root` account logs in with `sudo mysql` (no password, uses your system account).

### Enable `pdo_mysql` in PHP

```bash
php -m | grep -i pdo_mysql        # Windows: php -m | findstr pdo_mysql
```

If nothing shows: Windows → uncomment `extension=pdo_mysql` in `php.ini`; Linux → `sudo apt install php-mysql`. Homebrew's PHP already includes it.

## Talking to the server

The command-line client:

```bash
docker compose exec db mysql -u training -p training    # Docker (password: training)
mysql -u training -p training                            # native install
```

```sql
SELECT VERSION();
SHOW DATABASES;
USE training;
SHOW TABLES;
DESCRIBE books;
exit
```

Or use a GUI: Adminer (included in the Docker setup), phpMyAdmin, [DBeaver](https://dbeaver.io), TablePlus, or the VS Code extension *Database Client*.

To **design** tables as a diagram and generate the SQL, use [MySQL Workbench](https://dev.mysql.com/downloads/workbench/) (free). More in [useful tools](../useful-tools.md).

### Users and permissions

Never let your application connect as `root`. Create one user per application, with rights on its own database only:

```sql
CREATE DATABASE training CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'training'@'%' IDENTIFIED BY 'training';
GRANT ALL PRIVILEGES ON training.* TO 'training'@'%';
```

(The Docker setup already did this for you.)

## Writing tables for MySQL

MySQL is stricter about types than SQLite. A typical table:

```sql
CREATE TABLE books (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(255) NOT NULL,
    price      DECIMAL(8,2) NOT NULL DEFAULT 0,      -- money: DECIMAL, never FLOAT
    published  DATE NULL,
    in_stock   BOOLEAN NOT NULL DEFAULT TRUE,        -- stored as TINYINT(1)
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

| SQLite | MySQL |
|---|---|
| `INTEGER PRIMARY KEY AUTOINCREMENT` | `INT UNSIGNED AUTO_INCREMENT PRIMARY KEY` |
| `TEXT` for any string | `VARCHAR(n)` for short strings, `TEXT` for long ones |
| `REAL` | `DECIMAL(p,s)` for exact values (money), `DOUBLE` otherwise |
| dates stored as text | `DATE`, `DATETIME`, `TIMESTAMP` |

- **`utf8mb4`**, not `utf8`: MySQL's old `utf8` can't store emojis and some characters. `utf8mb4` is the real UTF-8.
- **InnoDB** is the engine that supports transactions and foreign keys (default in modern versions).

### Foreign keys and joins

A real database has several related tables. A foreign key guarantees that a book always points to an existing author:

```sql
CREATE TABLE authors (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE books (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title     VARCHAR(255) NOT NULL,
    author_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

```sql
-- Each book with its author's name
SELECT b.title, a.name AS author
FROM books b
JOIN authors a ON a.id = b.author_id
ORDER BY a.name, b.title;

-- Number of books per author
SELECT a.name, COUNT(b.id) AS nb_books
FROM authors a
LEFT JOIN books b ON b.author_id = a.id
GROUP BY a.id, a.name;
```

Keep your schema in a `.sql` file in the project, and load it with:

```bash
docker compose exec -T db mysql -u training -ptraining training < schema.sql   # Docker
mysql -u training -p training < schema.sql                                    # native
```

## Connecting from PHP

```php
$pdo = new PDO(
    'mysql:host=127.0.0.1;port=3306;dbname=training;charset=utf8mb4',
    'training',
    'training',
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,   // real server-side prepared statements
    ]
);
```

Three details that save hours:

- **`127.0.0.1`, not `localhost`**: on macOS/Linux, `localhost` makes PHP look for a local socket file instead of using the network, which fails with Docker (*"No such file or directory"*).
- **`charset=utf8mb4`** in the DSN, otherwise accents can be mangled.
- **`ATTR_EMULATE_PREPARES => false`**: by default PDO-MySQL *simulates* prepared statements and sends every value as a string. One visible symptom: `LIMIT ?` with `execute([5])` becomes `LIMIT '5'` and fails. Turn emulation off.

### Keep credentials out of the code

Passwords don't belong in files you commit to Git. A simple, common approach:

```php
// config.php — NOT committed (listed in .gitignore)
return [
    'dsn'      => 'mysql:host=127.0.0.1;dbname=training;charset=utf8mb4',
    'user'     => 'training',
    'password' => 'training',
];
```

```php
// config.example.php — committed, same keys, fake values, so others know what to fill in
```

```php
$config = require __DIR__ . '/config.php';
$pdo = new PDO($config['dsn'], $config['user'], $config['password'], $options);
```

Frameworks do the same with a `.env` file (`DATABASE_URL=mysql://...`).

### Common connection errors

| Message contains | Meaning | Fix |
|---|---|---|
| `could not find driver` | `pdo_mysql` not enabled | see *Enable pdo_mysql* |
| `[2002] No such file or directory` | `localhost` → socket | use `127.0.0.1` |
| `[2002] Connection refused` | server not running / wrong port | `docker compose ps`, check port |
| `[1045] Access denied` | wrong user or password | check credentials |
| `[1049] Unknown database` | database doesn't exist | `CREATE DATABASE` / check name |

## Backups

```bash
docker compose exec -T db mysqldump -u training -ptraining training > backup.sql   # export
docker compose exec -T db mysql -u training -ptraining training < backup.sql    # restore
```

(Native install: same commands without `docker compose exec db`.)

---

Now do the [exercises](exercises.md).
