<?php

/**
 * Application Configuration
 *
 * Priority:
 * 1. Environment variables (Docker / system)
 * 2. .env file in project root (local development)
 * 3. Hardcoded defaults (safe fallbacks)
 */

// Load .env file if it exists (local development without Docker)
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $key   = trim($key);
            $value = trim($value);
            // Only set if not already defined by actual environment
            if (!isset($_ENV[$key]) && getenv($key) === false) {
                $_ENV[$key] = $value;
                putenv("{$key}={$value}");
            }
        }
    }
}

return [
    'db' => [
        'host'    => $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: '127.0.0.1',
        'port'    => $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '3306',
        'name'    => $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: 'taskmanager',
        'user'    => $_ENV['DB_USER'] ?? getenv('DB_USER') ?: 'root',
        'pass'    => $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'env'   => $_ENV['APP_ENV'] ?? getenv('APP_ENV') ?: 'development',
        'debug' => filter_var($_ENV['APP_DEBUG'] ?? getenv('APP_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN),
    ],
];
