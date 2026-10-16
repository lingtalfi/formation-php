<?php
declare(strict_types=1);

// Loaded at the top of every page: errors, session, autoloader, helpers.

ini_set('display_errors', '1');   // development only
error_reporting(E_ALL);
date_default_timezone_set('Europe/Paris');   // or set date.timezone in php.ini

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

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}
