<?php

namespace app\database;

use PDO;
use PDOException;

class TransactionManager {
    private $connection;
    private $isTransactionStarted = false;

    // Dependency Injection via Constructor
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    public function beginTransaction(): bool {
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

    public function commit(): bool {
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

    public function rollBack(): bool {
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
