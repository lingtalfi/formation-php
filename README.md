# PHP — Build Dynamic Websites

A short, hands-on PHP course. You learn each concept by writing code, then compare with the provided solution.

It follows the program *Langage PHP – Développer des sites web* (ENI certification) and is designed to stay useful afterwards as a reference.

## Who this is for

You already know how to program (variables, loops, functions) in another language, and you know some HTML/CSS. You have never written PHP, or only a little.

## How the repo is organized

| Folder | Topic |
|---|---|
| [`00-setup`](00-setup/) | Install PHP and an editor (Windows, macOS, Linux) |
| [`01-introduction`](01-introduction/) | What PHP is, php.ini and extensions, PHP + HTML, variables, types, constants |
| [`02-procedural`](02-procedural/) | Conditions, loops, functions, arrays |
| [`03-oop`](03-oop/) | Classes, objects, inheritance, interfaces, namespaces |
| [`04-web`](04-web/) | Forms (GET/POST), sessions, cookies, file uploads, debugging |
| [`05-database`](05-database/) | PDO, prepared statements, CRUD, transactions — *bonus:* pagination, sorting, search, admin security |
| [`06-mysql`](06-mysql/) | *Optional:* MySQL server, users, types, joins, moving an app from SQLite to MySQL |
| [`07-final-project`](07-final-project/) | Bookshop app: catalog, secured admin, visit statistics — the closing test |
| [`cheatsheet.md`](cheatsheet.md) | Everything on one page |
| [`cheatsheet-modern-php.md`](cheatsheet-modern-php.md) | What changed from PHP 7.0 to 8.5, with versions |
| [`pdf/`](pdf/) | Printable PDF version of each module and the cheatsheet |

Each module contains:

- `README.md` — the lesson, with short examples
- `exercises.md` — what to build
- `solutions/` — one possible solution per exercise. **Try first, look after.**

## Running the code

Command-line scripts:

```bash
php path/to/script.php
```

Web pages (modules 1, 4 and 5), from the folder containing the page:

```bash
php -S localhost:8000
```

then open <http://localhost:8000> in your browser.

## One-day agenda (7h)

| Time | Module |
|---|---|
| 09:00 – 09:20 | Setup check (`00-setup`) |
| 09:20 – 10:00 | 01 — Introduction |
| 10:00 – 11:15 | 02 — Procedural PHP |
| 11:15 – 12:30 | 03 — OOP (part 1: classes, objects, inheritance) |
| *Lunch* | |
| 13:30 – 14:00 | 03 — OOP (part 2: interfaces, namespaces) |
| 14:00 – 15:30 | 04 — Web: forms, sessions, cookies, uploads |
| 15:30 – 16:45 | 05 — Database with PDO |
| 16:45 – 17:00 | Wrap-up, questions, what to study next |

Over two days, the second day is used for the final project (`07`), module 06 (MySQL) and exam preparation. On a single day, both are self-study.

## Requirements

PHP 8.2 or newer. All code in this repo is tested on PHP 8.3.

## Rebuilding the PDFs

The Markdown files are the source. After editing them, run `./tools/build-pdf.sh` (needs `pandoc` and `wkhtmltopdf`).
