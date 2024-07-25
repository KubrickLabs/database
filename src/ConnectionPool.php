<?php

namespace app\core\database;

use PDO;

class ConnectionPool {
    private static $pool = [];
    private static $maxPoolSize = 10;

    public static function getConnection($dsn, $username, $password, $options) {
        if (count(self::$pool) > 0) {
            return array_pop(self::$pool);
        }
        return new PDO($dsn, $username, $password, $options);
    }

    public static function releaseConnection($connection) {
        if (count(self::$pool) < self::$maxPoolSize) {
            self::$pool[] = $connection;
        }
    }
}
