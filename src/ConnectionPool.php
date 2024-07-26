<?php

namespace app\database;

use PDO;

/**
 * Class ConnectionPool
 * 
 * Manages a pool of PDO connections to optimize resource usage.
 */
class ConnectionPool {
    private static $pool = [];
    private static $maxPoolSize = 10;

    /**
     * Get a connection from the pool or create a new one if none are available.
     * 
     * @param string $dsn Data Source Name
     * @param string|null $username Database username
     * @param string|null $password Database password
     * @param array $options PDO options
     * @return PDO
     */
    public static function getConnection($dsn, $username, $password, $options) {
        if (count(self::$pool) > 0) {
            return array_pop(self::$pool);
        }
        return new PDO($dsn, $username, $password, $options);
    }

    /**
     * Release a connection back to the pool if there is space.
     * 
     * @param PDO $connection
     */
    public static function releaseConnection(PDO $connection) {
        if (count(self::$pool) < self::$maxPoolSize) {
            self::$pool[] = $connection;
        }
    }
}
