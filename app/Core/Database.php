<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Database — PDO Singleton
 *
 * Provides a single shared PDO connection for the whole application.
 * Uses prepared statements for all queries to prevent SQL injection.
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Private constructor — prevents direct instantiation.
     */
    private function __construct() {}

    /**
     * Returns the shared PDO instance, creating it if it doesn't exist.
     *
     * @throws \RuntimeException if the connection fails.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/config.php';
            $db     = $config['db'];

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $db['host'],
                $db['port'],
                $db['name'],
                $db['charset']
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $db['user'], $db['pass'], $options);
            } catch (PDOException $e) {
                // Log the real error, show a generic message to the user.
                error_log('Database connection failed: ' . $e->getMessage());
                throw new \RuntimeException('Database connection failed. Please try again later.');
            }
        }

        return self::$instance;
    }

    /**
     * Resets the singleton (used in tests or for reconnect scenarios).
     */
    public static function reset(): void
    {
        self::$instance = null;
    }
}
