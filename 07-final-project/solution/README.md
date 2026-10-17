# Bookshop — final project solution

A small but complete app: a public catalog with **search, sorting and pagination**, a **password-protected admin** to add, edit and delete books, and **visit statistics**.

## Run it

```bash
cd 07-final-project/solution
php -S localhost:8000
```

- Shop: <http://localhost:8000>
- Admin: <http://localhost:8000/admin/> — password `admin123`
- Statistics: <http://localhost:8000/admin/stats.php> (browse the shop first to create some visits)

The SQLite database `bookshop.db` is created on first run with 30 books and an empty `visits` table. Delete the file to start over.

## Files

```
solution/
├── index.php              public catalog: search + sort + pagination
├── style.css
├── admin/
│   ├── login.php          password form
│   ├── logout.php         POST only
│   ├── index.php          book list with edit / delete
│   ├── edit.php           add (no id) or edit (?id=12) a book
│   ├── delete.php         POST only
│   └── stats.php          visit statistics
└── src/
    ├── bootstrap.php      session, autoloader, helpers (e, url, csrf, pagination...)
    ├── Database.php       connection + tables + sample data
    ├── BookRepository.php all the SQL
    ├── BookValidator.php  form validation
    ├── Auth.php           admin login
    ├── Tracker.php        records each public page view ($_SERVER, shutdown function)
    └── StatsRepository.php  GROUP BY queries for the stats page
```

## Where to look

| Feature | File |
|---|---|
| `LIMIT` / `OFFSET` with integer binding | `BookRepository::search()` |
| Number of pages, page validation | `listing_params()` in `bootstrap.php` |
| Safe dynamic `ORDER BY` (whitelist) | `BookRepository::SORTABLE` |
| Search with `LIKE` | `BookRepository::searchCondition()` |
| Links that keep the other parameters | `url()`, `sort_link()`, `pagination()` |
| Login, session, protected pages | `Auth.php`, first line of each admin page |
| CSRF tokens on every POST form | `csrf_field()` / `csrf_check()` |
| One form for add and edit | `admin/edit.php` |
| Reading request info (`$_SERVER`), IP anonymization | `Tracker.php` |
| Recording after the page ends | `Tracker::start()` (`register_shutdown_function`) |
| Statistics with `GROUP BY`, `COUNT(DISTINCT ...)` | `StatsRepository.php` |
