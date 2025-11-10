<?php
declare(strict_types=1);

class SessionManager {
    private static bool $initialized = false;

    public static function start(): void {
        if (!self::$initialized) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            self::$initialized = true;
        }
    }

    public static function set(string $key, $value): void {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function destroy(): void {
        self::start();
        session_destroy();
        self::$initialized = false;
    }

    public static function isAuthenticated(): bool {
        return self::has('user') && self::get('login') === true;
    }
}