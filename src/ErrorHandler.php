<?php

namespace app\core\database;

use PDOException;

class ErrorHandler {
    public static function handleError(PDOException $e) {
        $errorLog = sprintf("[%s] Error: %s in %s:%d\n",
            date('Y-m-d H:i:s'), $e->getMessage(), $e->getFile(), $e->getLine());
        error_log($errorLog, 3, 'errors.log');
        echo "An error occurred. Please try again later.";
    }
}
