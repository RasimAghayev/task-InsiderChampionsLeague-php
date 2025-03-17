<?php

namespace App\Infrastructure\Database;

class DatabaseConnection
{
    /**
     * @var \App\Infrastructure\Database\DatabaseConnection|null
     */
    private static ?self $instance = null;
    /**
     * @var \PDO
     */
    private \PDO $connection;

    /**
     *
     */
    private function __construct()
    {
        $config = require __DIR__ . '/../../../config/config.php';
        $dbConfig = $config['database'];

        $dsn = "{$dbConfig['driver']}:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']}";

        try {
            $this->connection = new \PDO(
                $dsn,
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['options']
            );
        } catch (\PDOException $e) {
            throw new \RuntimeException("Database connection error: " . $e->getMessage());
        }
    }

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        return $this->connection;
    }

    // Prevent cloning

    /**
     * @return void
     */
    private function __clone() {}

    // Prevent unserialization

    /**
     * @return mixed
     */
    public function __wakeup()
    {
        throw new \RuntimeException("Cannot unserialize singleton");
    }
}