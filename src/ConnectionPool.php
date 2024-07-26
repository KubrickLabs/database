<?php

namespace app\database;

use PDO;

/**
 * Class ConnectionPool
 *
 * Manages a pool of PDO connections to optimize database access.
 *
 * @package app\database
 */
class ConnectionPool
{
    /**
     * @var PDO[] Array to hold the pool of connections.
     */
    private static $pool = [];

    /**
     * @var int Maximum number of connections in the pool.
     */
    private static $maxPoolSize = 10;

    /**
     * Get a connection from the pool or create a new one if the pool is empty.
     *
     * @param string $dsn The Data Source Name, or DSN, contains the information required to connect to the database.
     * @param string|null $username The user name for the DSN string. This parameter is optional for some PDO drivers.
     * @param string|null $password The password for the DSN string. This parameter is optional for some PDO drivers.
     * @param array|null $options A key=>value array of driver-specific connection options.
     * @return PDO Returns a PDO connection.
     */
    public static function getConnection($dsn, $username = null, $password = null, $options = null): PDO
    {
        if (count(self::$pool) > 0) {
            return array_pop(self::$pool);
        }
        return new PDO($dsn, $username, $password, $options);
    }

    /**
     * Release a connection back to the pool.
     *
     * @param PDO $connection The PDO connection to release.
     * @return void
     */
    public static function releaseConnection(PDO $connection): void
    {
        if (count(self::$pool) < self::$maxPoolSize) {
            self::$pool[] = $connection;
        }
    }
}