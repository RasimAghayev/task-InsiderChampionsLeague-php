<?php

namespace App\Infrastructure\Database;

use PDO;
use PDOException;
use RuntimeException;

class DatabaseConnection
{
    private static ?self $instance = null;
    private PDO $connection;

    /**
     * @param string|null $configPath
     */
        private function __construct(?string $configPath = null)
    {
        $configPath = $configPath ?? __DIR__ . '/../../../config/config.php';
        $config = require $configPath;
        $dbConfig = $config['database'];

        $dsn = "{$dbConfig['driver']}:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']}";

        try {
            $this->connection = new PDO(
                $dsn,
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['options']
            );
        } catch (PDOException $e) {
            throw new RuntimeException("Database connection error: " . $e->getMessage());
        }
    }

    /**
     * @param string|null $configPath
     * @return self
     */
    public static function getInstance(?string $configPath = null): self
    {
        if (self::$instance === null) {
            self::$instance = new self($configPath);
        }
        return self::$instance;
    }

    /**
     * Resets the singleton instance for testing purposes.
     *
     * @return void
     */
    public static function resetInstance(): void
    {
        self::$instance = null;
    }

    /**
     * @return \PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    // Prevent cloning

    /**
     * @return mixed
     */
    public function __wakeup()
    {
        throw new RuntimeException("Cannot unserialize singleton");
    }

    // Prevent unserialization

    /**
     * @return void
     */
    private function __clone()
    {
    }
}