<?php
declare(strict_types=1);

namespace App;

/**
 * Minimal admin authentication: one password, stored as a hash.
 *
 * Password: admin123
 * To change it, generate a new hash and paste it below:
 *     php -r "echo password_hash('your-new-password', PASSWORD_DEFAULT);"
 * In a real app, the hash would be in a config file or a users table, not in the code.
 */
class Auth
{
    private const PASSWORD_HASH = '$2y$10$BU9QATOLcsxlR/zO74XXhuag.QcrnU/WI2U0ATUZwaP2d/m4H6EeW';

    public static function attempt(string $password): bool
    {
        if (!password_verify($password, self::PASSWORD_HASH)) {
            usleep(500_000);   // slow down password guessing a little
            return false;
        }
        session_regenerate_id(true);   // new session id after login: prevents session fixation
        $_SESSION['admin'] = true;
        return true;
    }

    public static function check(): bool
    {
        return ($_SESSION['admin'] ?? false) === true;
    }

    /** Call at the top of every admin page. */
    public static function requireAdmin(): void
    {
        if (!self::check()) {
            redirect('login.php');
        }
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}
