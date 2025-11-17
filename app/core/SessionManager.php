<?php
declare(strict_types=1);

class SessionManager {
    private static bool $initialized = false;

    static function start(): void {
        if (!self::$initialized) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            self::$initialized = true;
        }
    }

    static function set(string $key, $value): void {
        self::start();
        $_SESSION[$key] = $value;
    }

    static function get(string $key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    static function has(string $key): bool {
        self::start();
        return isset($_SESSION[$key]);
    }

    static function remove(string $key): void {
        self::start();
        unset($_SESSION[$key]);
    }

    static function destroy(): void {
        self::start();
        session_destroy();
        self::$initialized = false;
    }

    static function auth(int $id, string $nombre, string $email): void {
        self::set('login', true);
        self::set('user', ['id' => $id, 'nombre' => $nombre, 'email' => $email]);
    }

    static function isAuthenticated(): bool {
        return self::has('user') && self::get('login') === true;
    }
}

?>