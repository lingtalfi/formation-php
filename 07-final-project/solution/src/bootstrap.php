<?php
declare(strict_types=1);

// Loaded at the top of every page: errors, session, autoloader, helpers.

ini_set('display_errors', '1');   // development only
error_reporting(E_ALL);
date_default_timezone_set('Europe/Paris');

session_start();

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (str_starts_with($class, $prefix)) {
        $file = __DIR__ . '/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        if (is_file($file)) {
            require $file;
        }
    }
});

/** Escape for HTML output. */
function e(string|int|float|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/** Price as "12,50 €". */
function price(float|string $value): string
{
    return number_format((float) $value, 2, ',', ' ') . ' €';
}

/**
 * URL of the current page with some query parameters changed, the others kept.
 * Example: on ?q=dune&sort=year, url(['page' => 2]) gives ?q=dune&sort=year&page=2
 */
function url(array $changes): string
{
    $params = array_merge($_GET, $changes);
    $params = array_filter($params, fn($v) => $v !== null && $v !== '');   // drop empty ones
    return '?' . http_build_query($params);
}

// --- Flash messages: stored in the session, shown once on the next page

function flash(string $message): void
{
    $_SESSION['flash'] = $message;
}

function take_flash(): ?string
{
    $message = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $message;
}

// --- CSRF protection
// A random token is stored in the session and added to every POST form.
// A malicious site can make your browser POST to this app, but it cannot read the token,
// so its requests are rejected.

function csrf_token(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

function csrf_check(): void
{
    if (!hash_equals(csrf_token(), $_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('Invalid form token. Go back, reload the page and try again.');
    }
}

// --- Listing helpers: sorting and pagination, shared by the catalog and the admin

/**
 * Reads ?q=, ?sort=, ?dir= and ?page= from the URL, validated.
 * Returns everything a listing page needs.
 */
function listing_params(App\BookRepository $repo, int $perPage): array
{
    $q = trim($_GET['q'] ?? '');

    $sort = $_GET['sort'] ?? 'title';
    if (!array_key_exists($sort, App\BookRepository::SORTABLE)) {
        $sort = 'title';
    }
    $dir = ($_GET['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

    $total = $repo->count($q);
    $pages = max(1, (int) ceil($total / $perPage));
    $page = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;
    $page = min($page, $pages);

    $books = $repo->search($q, $sort, $dir, $perPage, ($page - 1) * $perPage);

    return compact('q', 'sort', 'dir', 'total', 'pages', 'page', 'books');
}

/**
 * A clickable column header. Clicking the current column flips the direction.
 * The page goes back to 1: page 3 of a new sort order means nothing.
 */
function sort_link(string $column, string $label, string $sort, string $dir): string
{
    $newDir = ($sort === $column && $dir === 'asc') ? 'desc' : 'asc';
    $arrow = $sort === $column ? ($dir === 'asc' ? ' ▲' : ' ▼') : '';
    $href = url(['sort' => $column, 'dir' => $newDir, 'page' => null]);
    return '<a href="' . e($href) . '">' . e($label) . $arrow . '</a>';
}

/** Previous / numbers / next links. Keeps the search and the sort in every link. */
function pagination(int $page, int $pages): string
{
    if ($pages <= 1) {
        return '';
    }
    $html = '<nav class="pages">';
    if ($page > 1) {
        $html .= '<a href="' . e(url(['page' => $page - 1])) . '">&laquo; Previous</a>';
    }
    for ($i = 1; $i <= $pages; $i++) {
        $html .= $i === $page
            ? '<span class="current">' . $i . '</span>'
            : '<a href="' . e(url(['page' => $i])) . '">' . $i . '</a>';
    }
    if ($page < $pages) {
        $html .= '<a href="' . e(url(['page' => $page + 1])) . '">Next &raquo;</a>';
    }
    return $html . '</nav>';
}
