# Bookshop — bonus project solution

A small but complete app: a public catalog with **search, sorting and pagination**, and a **password-protected admin** to add, edit and delete books.

## Run it

```bash
cd 05-database/solutions/bookshop
php -S localhost:8000
```

- Shop: <http://localhost:8000>
- Admin: <http://localhost:8000/admin/> — password `admin123`

The SQLite database `bookshop.db` is created on first run with 30 books. Delete the file to start over.

## Files

```
bookshop/
├── index.php              public catalog: search + sort + pagination
├── style.css
├── admin/
│   ├── login.php          password form
│   ├── logout.php         POST only
│   ├── index.php          book list with edit / delete
│   ├── edit.php           add (no id) or edit (?id=12) a book
│   └── delete.php         POST only
└── src/
    ├── bootstrap.php      session, autoloader, helpers (e, url, csrf, pagination...)
    ├── Database.php       connection + table creation + sample data
    ├── BookRepository.php all the SQL
    ├── BookValidator.php  form validation
    └── Auth.php           admin login
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
