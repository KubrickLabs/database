<?php

namespace app\core\database;

use PDO;
use PDOException;

class TransactionManager {
    private $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    public function beginTransaction() {
        try {
            $this->connection->beginTransaction();
            return true;
        } catch (PDOException $e) {
            ErrorHandler::handleError($e);
            return false;
        }
    }

    public function commit() {
        try {
            $this->connection->commit();
            return true;
        } catch (PDOException $e) {
            ErrorHandler::handleError($e);
            return false;
        }
    }

    public function rollBack() {
        try {
            return $this->connection->rollBack();
        } catch (PDOException $e) {
            ErrorHandler::handleError($e);
            return false;
        }
    }
}
