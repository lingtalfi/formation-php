# 04 — Exercises

Run `php -S localhost:8000` in your working folder and open the pages in the browser.

## 4.1 — Search page (GET)

Create `search.php` with a search field (`name="q"`) and `method="get"`.

- When a search is submitted, display `You searched for: ...` under the form.
- Filter this list and display only the matching items (case-insensitive, `stripos` or `str_contains` + `strtolower`):

  ```php
  $languages = ['PHP', 'Python', 'Java', 'JavaScript', 'Go', 'Rust', 'Ruby'];
  ```

- The search field must keep the searched text after submitting.
- Test the security: search for `<script>alert('hacked')</script>`. Nothing should pop up.

## 4.2 — Contact form (POST)

Create `contact.php` with fields: name, email, subject (a `<select>` with 3 options), message.

Server-side validation:

- name: required
- email: required and valid (`filter_var`)
- subject: must be one of the 3 allowed values (don't trust the `<select>`)
- message: at least 10 characters

If there are errors, show each error next to its field and keep the values the user typed.
If everything is valid, redirect to `thanks.php` (Post/Redirect/Get), which displays a confirmation.

Bonus: pass the sender's name to `thanks.php` through the session, and display it once ("flash message").

## 4.3 — Login with sessions

Create 3 pages:

- `login.php`: a login form. Valid credentials: `admin` / `secret123`. Store the **hash** of the password in your code (generate it once with `password_hash` in a separate script), and check with `password_verify`.
- `dashboard.php`: only accessible when logged in. Displays `Welcome, admin` and a logout link. If not logged in, redirect to `login.php`.
- `logout.php`: destroys the session and redirects to `login.php`.

Check in DevTools (Application → Cookies) that a `PHPSESSID` cookie appears.

## 4.4 — Theme preference (cookie)

Create `theme.php` with two links: `?theme=light` and `?theme=dark`.

- Clicking a link saves the choice in a cookie for 30 days, then redirects to `theme.php` (without the parameter).
- The page uses the cookie value to set its colors (white or dark background).
- Only accept `light` or `dark` as values.
- Close the browser, come back: the theme is remembered.

## 4.5 — Avatar upload

Create `upload.php`:

- A form to upload an image.
- Accept only JPEG, PNG or WebP (checked with `finfo`), max 2 MB.
- Save it into an `uploads/` folder (create it with `mkdir` if it doesn't exist) under a random name.
- Display the list of uploaded images as thumbnails (`scandir` or `glob`).
- Test the security: rename a `.txt` file to `.jpg` and try to upload it. It must be rejected.

## 4.6 — Bug hunt

Copy [`buggy.php`](buggy.php) into your folder and open it in the browser. It's supposed to display a list of users with their age and the average age.

It contains **5 bugs**. Use error messages, `var_dump` and `php -l` to find and fix them all.
