<?php

namespace app\database;

use PDOException;

/**
 * Class ErrorHandler
 *
 * Handles errors by logging them to a file.
 *
 * @package app\database
 */
class ErrorHandler
{
    /**
     * Handle a PDOException by logging the error.
     *
     * @param PDOException $e The exception to handle.
     * @return void
     */
    public static function handleError(PDOException $e): void
    {
        $errorLog = sprintf(
            "[%s] Error: %s in %s:%d\n",
            date('Y-m-d H:i:s'),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );
        error_log($errorLog, 3, 'errors.log');
        echo "An error occurred. Please try again later.";
    }
}