# 00 — Setup

Goal: run `php -v` in a terminal and see version **8.2 or newer**, then run the check script.

Do this **before** the training day. If something blocks, bring the error message: we fix it in the first 20 minutes.

You need three things:

1. PHP (the language, used from the command line)
2. A code editor (VS Code recommended)
3. A browser (any)

No Apache, no MySQL server, no XAMPP: PHP has a built-in web server, and we use SQLite as database (a single file, nothing to install).

---

## macOS

1. Install [Homebrew](https://brew.sh) if you don't have it (paste the command from their homepage in Terminal).
2. Install PHP:

   ```bash
   brew install php
   ```

3. Close and reopen Terminal, then:

   ```bash
   php -v
   ```

> **Easy alternative:** [Laravel Herd](https://herd.laravel.com) installs PHP with one click (free version is enough). Ignore everything about Laravel, we only use the `php` command it provides.

## Windows

**Option A — easiest: Laravel Herd**

1. Download and install [Laravel Herd for Windows](https://herd.laravel.com/windows) (free version).
2. Open a **new** terminal (PowerShell) and run `php -v`.

**Option B — official zip**

1. Go to <https://windows.php.net/download/> and download the latest **VS16/VS17 x64 Non Thread Safe** zip.
2. Unzip it to `C:\php` (so you have `C:\php\php.exe`).
3. In `C:\php`, copy `php.ini-development` and rename the copy to `php.ini`.
4. Open `php.ini` in a text editor, find these lines and remove the `;` at the start:

   ```ini
   extension_dir = "ext"
   extension=fileinfo
   extension=mbstring
   extension=pdo_sqlite
   extension=sqlite3
   ```

5. Add `C:\php` to your PATH: Start menu → type *environment variables* → *Edit the system environment variables* → *Environment Variables…* → under *User variables*, select `Path` → *Edit* → *New* → `C:\php` → OK everywhere.
6. Open a **new** PowerShell window and run `php -v`.

> If `php -v` says *"php is not recognized"*: the PATH step didn't work, or the terminal was opened before the change. Close all terminals and try again.

> If you see an error about `VCRUNTIME140.dll`: install the *Microsoft Visual C++ Redistributable* (x64), linked on the same download page.

## Linux (Debian / Ubuntu)

```bash
sudo apt update
sudo apt install php-cli php-sqlite3 php-mbstring
php -v
```

Fedora: `sudo dnf install php-cli php-pdo php-mbstring`

If the version shown is older than 8.2, tell the trainer before the session.

---

## Editor: VS Code

1. Install [VS Code](https://code.visualstudio.com).
2. Install the extension **PHP Intelephense** (by Ben Mewburn): autocompletion and error highlighting.
3. Open the terminal inside VS Code: menu *Terminal → New Terminal*. That's where you run `php` commands.

Any other editor (PhpStorm, Zed, Sublime…) is fine if you already use one.

---

## Get the course files

Either clone the repo:

```bash
git clone https://github.com/lingtalfi/formation-php.git
```

or download the ZIP from the GitHub page (green *Code* button → *Download ZIP*) and unzip it.

## Final check

From the course folder:

```bash
php 00-setup/check.php
```

You should see only `OK` lines. Then test the web server:

```bash
cd 00-setup
php -S localhost:8000
```

Open <http://localhost:8000/hello.php>. You should see a greeting with the current time. Stop the server with `Ctrl+C`.

> **Port already in use?** Use another one: `php -S localhost:8080`.
