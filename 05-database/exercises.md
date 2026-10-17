# 05 — Exercises

## 5.1 — Library (command line)

Create `library.php`, run with `php library.php`.

1. Connect to a SQLite file `library.db` (with the two options `ERRMODE_EXCEPTION` and `FETCH_ASSOC`).
2. Create a `books` table (id, title, author, year) if it doesn't exist.
3. If the table is empty, insert these books with **one** prepared statement executed in a loop:

   ```php
   $books = [
       ['1984', 'George Orwell', 1949],
       ['Dune', 'Frank Herbert', 1965],
       ['The Hobbit', 'J.R.R. Tolkien', 1937],
       ['Neuromancer', 'William Gibson', 1984],
       ['Foundation', 'Isaac Asimov', 1951],
   ];
   ```

4. Display all books sorted by year: `1937 - The Hobbit (J.R.R. Tolkien)`.
5. Display the number of books (`COUNT(*)` + `fetchColumn`).
6. Display the books published after 1950 (prepared statement with a placeholder).
7. Update the year of *Dune* to 1966, display the number of modified rows.
8. Delete *Neuromancer*, then display the list again.

Run the script twice: the books must not be inserted twice.

## 5.2 — Transfer with a transaction

Create `transfer.php`.

1. Create an `accounts` table (id, owner, balance) with two accounts: Alice 500, Bob 100. Recreate it at each run (`DROP TABLE IF EXISTS`) so you always start from the same state.
2. Write a function `transfer(PDO $pdo, int $from, int $to, float $amount): void` that, inside a transaction:
   - checks that the source account has enough money (otherwise throw an exception),
   - debits the source, credits the destination,
   - commits, or rolls back on any error.
3. Test: transfer 200 from Alice to Bob (works), then 1000 from Bob to Alice (fails). Display the balances after each attempt: the failed transfer must not change anything.

## 5.3 — Final project: Guestbook

Build a small web app, in a folder `guestbook/`, combining everything from the course:

```
guestbook/
├── index.php          list of messages + form to add one
├── admin.php          login (session), then list with "delete" buttons
├── logout.php
└── src/
    ├── bootstrap.php          session_start, autoloader, e() helper — required by every page
    ├── Database.php           returns the PDO connection (creates the table if needed)
    └── MessageRepository.php  findAll(), add(), delete()
```

Requirements:

- `messages` table: id, author, content, created_at.
- **index.php**: form (author, message), server-side validation, errors displayed, values kept, Post/Redirect/Get after saving. Messages displayed newest first, with date formatted `16/10/2026 14:30`. Everything escaped.
- **admin.php**: protected by a password (session + `password_verify`). Each message has a delete button (a small `POST` form with the message id: never delete with a GET link).
- All SQL in `MessageRepository`, all queries prepared when they include data.
- Classes in a namespace (`App\`) and loaded with an autoloader.

Bonus: pagination, 5 messages per page with `LIMIT` / `OFFSET` and *Newer / Older* links. Read the *Bonus: pagination* section of the lesson first. The provided solution includes it.

## 5.4 — Bonus project: Bookshop

For those who finished early. Build a small bookshop app with a public catalog and an admin area. This is the kind of app you'd actually be asked to build at work.

**Catalog (`index.php`)**

- A `books` table (id, title, author, year, price, created_at), created with some sample books on first run (20+, to have several pages).
- Books displayed in a table, **8 per page**, with *Previous / 1 2 3 / Next* links.
- A **search** field filtering on title or author.
- **Sortable columns**: clicking *Title*, *Author*, *Year* or *Price* sorts by that column; clicking again reverses the order. Show an arrow ▲▼ on the current column.
- Search, sort and page must work **together**: changing page keeps the search and the sort; a new search or sort goes back to page 1.
- Robustness: `?page=abc`, `?page=999`, `?sort=hack` must not break anything.

**Admin (`admin/`)**

- `login.php`: password form (hash + `password_verify`, `session_regenerate_id` after login).
- Every admin page redirects to the login when not logged in.
- `index.php`: the same list, with *Edit* and *Delete* buttons, and an *Add a book* link.
- `edit.php`: one form for both adding (no `id`) and editing (`?id=12`), with validation (title and author required, year optional but valid, price ≥ 0 and accepting `12,50`).
- Delete and logout are **POST** forms, never links.
- All POST forms protected by a **CSRF token**.
- Flash messages after each action ("“Dune” updated.").

Solution: [`solutions/bookshop/`](solutions/bookshop/).
