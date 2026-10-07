<?php

namespace App\Core;

/**
 * Request — Wrapper to safely read HTTP input.
 *
 * Always use this class instead of reading $_GET/$_POST directly,
 * so all input access is consistent and can be traced easily.
 */
class Request
{
    /**
     * Get a value from GET parameters.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Get a value from POST parameters.
     */
    public static function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Get all POST parameters.
     */
    public static function all(): array
    {
        return $_POST;
    }

    /**
     * Get the HTTP method (GET, POST, etc.).
     */
    public static function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Returns true if the current request is a POST.
     */
    public static function isPost(): bool
    {
        return self::method() === 'POST';
    }

    /**
     * Returns true if the current request expects JSON (AJAX).
     */
    public static function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Get the current page (route).
     */
    public static function page(): string
    {
        return self::get('page', 'dashboard');
    }

    /**
     * Get the current action.
     */
    public static function action(): string
    {
        return self::get('action', 'index');
    }
}
