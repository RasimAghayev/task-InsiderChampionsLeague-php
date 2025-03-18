<?php

namespace App\Tests\Infrastructure\Database;

use App\Infrastructure\Database\DatabaseConnection;
use PDO;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DatabaseConnectionTest extends TestCase
{
    /**
     * @return void
     */
    public function testSingletonInstance(): void
    {
        $instance1 = DatabaseConnection::getInstance(__DIR__ . '/../../../config/test_config.php');
        $instance2 = DatabaseConnection::getInstance(__DIR__ . '/../../../config/test_config.php');

        $this->assertSame($instance1, $instance2);
    }

    /**
     * @return void
     */
    public function testGetConnection(): void
    {
        $instance = DatabaseConnection::getInstance(__DIR__ . '/../../../config/test_config.php');
        $connection = $instance->getConnection();

        $this->assertInstanceOf(PDO::class, $connection);

        $this->assertEquals(PDO::ERRMODE_EXCEPTION, $connection->getAttribute(PDO::ATTR_ERRMODE));
    }


    /**
     * @return void
     */
    public function testInvalidDatabaseConnection(): void
    {
        // Reset the singleton instance to ensure a fresh start
        DatabaseConnection::resetInstance();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/Database connection error/');

        // Attempt to create a connection with invalid configuration
        DatabaseConnection::getInstance(__DIR__ . '/../../../config/invalid_config.php');
    }
}