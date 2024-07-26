<?php

namespace app\database;

use PDO;
use PDOException;


/**
 * Class TransactionManager
 * 
 * Manages database transactions.
 */
class TransactionManager {
    private $connection;
    private $isTransactionStarted = false;

    /**
     * TransactionManager constructor.
     * 
     * @param PDO $connection
     */
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * Begin a transaction.
     * 
     * @return bool Success status
     */
    public function beginTransaction() {
        if ($this->isTransactionStarted) {
            throw new \RuntimeException('Transaction already started.');
        }

        try {
            $this->connection->beginTransaction();
            $this->isTransactionStarted = true;
            return true;
        } catch (PDOException $e) {
            ErrorHandler::handleError($e);
            return false;
        }
    }

    /**
     * Commit a transaction.
     * 
     * @return bool Success status
     */
    public function commit() {
        if (!$this->isTransactionStarted) {
            throw new \RuntimeException('No transaction started.');
        }

        try {
            $this->connection->commit();
            $this->isTransactionStarted = false;
            return true;
        } catch (PDOException $e) {
            $this->rollBack(); // Rollback in case of commit failure
            ErrorHandler::handleError($e);
            return false;
        }
    }

    /**
     * Rollback a transaction.
     * 
     * @return bool Success status
     */
    public function rollBack() {
        if (!$this->isTransactionStarted) {
            throw new \RuntimeException('No transaction started.');
        }

        try {
            $this->connection->rollBack();
            $this->isTransactionStarted = false;
            return true;
        } catch (PDOException $e) {
            ErrorHandler::handleError($e);
            return false;
        }
    }
}
