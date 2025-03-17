<?php

class Database
{
    private static $instance = null;
    private $connection;

    /**
     *
     */
    private function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        $dsn = "pgsql:host=localhost;port=6432;dbname=football_league;user=root;password=root";
        try {
            $this->connection = new PDO($dsn);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    /**
     * @return \Database|null
     */
    public static function getInstance(): ?Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * @return mixed
     */
    public function getConnection(): mixed
    {
        return $this->connection;
    }

    /**
     * @return void
     */
    private function __clone()
    {
    }
}

