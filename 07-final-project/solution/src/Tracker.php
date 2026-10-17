<?php
declare(strict_types=1);

namespace App;

use PDO;
use Throwable;

/**
 * Records one row per page view in the `visits` table.
 *
 * Everything comes from $_SERVER, the array PHP fills with information about
 * the request (sent by the browser) and the server. Typical keys:
 *
 *   REMOTE_ADDR           IP address of the visitor (or of the last proxy)
 *   REQUEST_METHOD        GET, POST...
 *   REQUEST_URI           path + query string: /index.php?q=dune&page=2
 *   HTTP_USER_AGENT       browser and OS, as announced by the browser (can be faked)
 *   HTTP_REFERER          page the visitor came from (often missing)
 *   HTTP_ACCEPT_LANGUAGE  preferred languages: fr-FR,fr;q=0.9,en;q=0.8
 *   REQUEST_TIME_FLOAT    timestamp of the start of the request, in microseconds
 *
 * Tip: var_dump($_SERVER) on any page to see them all.
 */
class Tracker
{
    private static float $start;

    /** Call once at the start of each page. The row is written when the page has finished. */
    public static function start(): void
    {
        self::$start = $_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true);

        // Runs after the page is sent, even after exit() or redirect():
        // we can then know the final HTTP status code and how long the page took.
        register_shutdown_function([self::class, 'record']);
    }

    public static function record(): void
    {
        try {
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
            parse_str(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY) ?? '', $query);

            $stmt = Database::connection()->prepare(
                'INSERT INTO visits
                    (visited_at, ip, method, path, search, status, duration_ms,
                     referer, user_agent, browser, os, language, visitor_id)
                 VALUES
                    (:visited_at, :ip, :method, :path, :search, :status, :duration_ms,
                     :referer, :user_agent, :browser, :os, :language, :visitor_id)'
            );
            $stmt->execute([
                'visited_at'  => date('Y-m-d H:i:s'),
                'ip'          => self::anonymizeIp($_SERVER['REMOTE_ADDR'] ?? ''),
                'method'      => $_SERVER['REQUEST_METHOD'] ?? 'GET',
                'path'        => $path,
                'search'      => is_string($query['q'] ?? null) && $query['q'] !== '' ? mb_substr($query['q'], 0, 100) : null,
                'status'      => http_response_code() ?: 200,
                'duration_ms' => (int) round((microtime(true) - self::$start) * 1000),
                'referer'     => isset($_SERVER['HTTP_REFERER']) ? mb_substr($_SERVER['HTTP_REFERER'], 0, 255) : null,
                'user_agent'  => mb_substr($userAgent, 0, 255),
                'browser'     => self::browser($userAgent),
                'os'          => self::os($userAgent),
                'language'    => self::language($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? ''),
                'visitor_id'  => self::visitorId(),
            ]);
        } catch (Throwable $e) {
            // Statistics must never break the site: log the problem and move on
            error_log('Tracker: ' . $e->getMessage());
        }
    }

    /**
     * An IP address is personal data (GDPR). Keeping it whole is rarely needed for statistics,
     * so we drop the last part: 192.168.1.42 -> 192.168.1.0
     * IPv6: keep the first 3 blocks only.
     */
    public static function anonymizeIp(string $ip): string
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return preg_replace('/\.\d+$/', '.0', $ip);
        }
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $packed = inet_pton($ip);
            return inet_ntop(substr($packed, 0, 6) . str_repeat("\0", 10));
        }
        return 'unknown';
    }

    /**
     * Very rough browser detection. Order matters: Edge's user agent also contains "Chrome",
     * and Chrome's also contains "Safari". Real projects use a library for this.
     */
    public static function browser(string $ua): string
    {
        return match (true) {
            $ua === ''                             => 'Unknown',
            (bool) preg_match('/bot|crawl|spider/i', $ua) => 'Bot',
            str_contains($ua, 'curl')              => 'curl',
            str_contains($ua, 'Edg/')              => 'Edge',
            str_contains($ua, 'Firefox/')          => 'Firefox',
            str_contains($ua, 'Chrome/')           => 'Chrome',
            str_contains($ua, 'Safari/')           => 'Safari',
            default                                => 'Other',
        };
    }

    public static function os(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'Windows')                              => 'Windows',
            str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')  => 'iOS',
            str_contains($ua, 'Mac OS X')                             => 'macOS',
            str_contains($ua, 'Android')                              => 'Android',
            str_contains($ua, 'Linux')                                => 'Linux',
            default                                                   => 'Other',
        };
    }

    /** "fr-FR,fr;q=0.9,en;q=0.8" -> "fr" (first preferred language) */
    public static function language(string $header): ?string
    {
        if ($header === '') {
            return null;
        }
        return strtolower(substr($header, 0, 2));
    }

    /**
     * Identifies a visitor across pages without storing anything personal:
     * a random id kept in the session. Counting distinct ids = counting visitors.
     */
    private static function visitorId(): string
    {
        return $_SESSION['visitor_id'] ??= bin2hex(random_bytes(8));
    }
}
