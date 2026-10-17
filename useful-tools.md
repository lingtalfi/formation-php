# Useful Tools

Tools that make PHP development easier. None is required for the course, but most of them are used daily by PHP developers.

## Designing databases

### MySQL Workbench

[Download (free Community edition)](https://dev.mysql.com/downloads/workbench/) — Windows, macOS, Linux

Draw your tables, columns and relations as a diagram, and let Workbench generate the `CREATE TABLE` SQL for you. It also works the other way: connect it to an existing database and it draws the diagram.

Useful vocabulary:

- **EER diagram**: the visual model of your tables and their relations.
- **Forward engineer**: diagram → SQL script / database.
- **Reverse engineer**: existing database → diagram.

*Demonstrated by the trainer during the session.*

## Browsing and querying databases

| Tool | Databases | Notes |
|---|---|---|
| [DB Browser for SQLite](https://sqlitebrowser.org) | SQLite | Open the `.db` files of modules 05 and 07 |
| VS Code extension *SQLite Viewer* | SQLite | Click a `.db` file in VS Code |
| [Adminer](https://www.adminer.org) | MySQL, PostgreSQL, SQLite… | A single PHP file; included in the module 06 Docker setup |
| [phpMyAdmin](https://www.phpmyadmin.net) | MySQL / MariaDB | Web interface found on most PHP hosting |
| [DBeaver](https://dbeaver.io) | almost all | Free, powerful desktop client |
| MySQL Workbench | MySQL | Also a good query tool (see above) |

## Editor

| Tool | What for |
|---|---|
| [VS Code](https://code.visualstudio.com) | Free editor used in this course |
| *PHP Intelephense* (VS Code extension) | Autocompletion, go to definition, error highlighting |
| *PHP Debug* (VS Code extension) + [Xdebug](https://xdebug.org) | Breakpoints and step-by-step debugging |
| [PhpStorm](https://www.jetbrains.com/phpstorm/) | The reference PHP IDE (paid, free for students) |

## Local PHP environment

| Tool | What for |
|---|---|
| [Laravel Herd](https://herd.laravel.com) | PHP in one click on Windows and macOS (works for any PHP project) |
| [Docker Desktop](https://www.docker.com/products/docker-desktop/) | Run MySQL, PostgreSQL or a whole server stack in containers (module 06) |

## The PHP ecosystem

| Tool | What for |
|---|---|
| [Composer](https://getcomposer.org) | PHP's package manager: install libraries, autoload your classes (next step after this course) |
| [Packagist](https://packagist.org) | The catalog of libraries installable with Composer |
| [PHPStan](https://phpstan.org) | Finds bugs in your code without running it |
| [PHP-CS-Fixer](https://cs.symfony.com) | Formats your code automatically to a standard style |
| [PHPUnit](https://phpunit.de) | Automated tests |

## Learning and reference

| Resource | What for |
|---|---|
| [php.net/manual](https://www.php.net/manual/en/) | The official documentation. Tip: `php.net/str_contains` opens a function's page directly |
| [PHP: The Right Way](https://phptherightway.com) | Good practices, in short chapters |
| [3v4l.org](https://3v4l.org) | Run a PHP snippet online on every PHP version: perfect to check "since which version does this work?" |
| [regex101.com](https://regex101.com) | Test regular expressions (choose the *PCRE* flavor, the one PHP uses) |
