<?php

namespace app\database;

use PDOException;

/**
 * Class ErrorHandler
 * 
 * Handles errors by logging them to a file.
 */
class ErrorHandler {
    /**
     * Handle a PDOException by logging the error.
     * 
     * @param PDOException $e
     */
    public static function handleError(PDOException $e) {
        $errorLog = sprintf("[%s] Error: %s in %s:%d\n",
            date('Y-m-d H:i:s'), $e->getMessage(), $e->getFile(), $e->getLine());
        error_log($errorLog, 3, 'errors.log');
        echo "An error occurred. Please try again later.";
    }
}
