<?php

namespace app\database;

use PDO;
use PDOException;

class Database extends PDO {
    private static $instance = null;
    private $crud;
    private $transaction;

    public function __construct($dsn, $username = null, $password = null, $options = null) {
        parent::__construct($dsn, $username, $password, $options);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->crud = new CRUDOperations($this);
        $this->transaction = new TransactionManager($this);
    }

    // Transaction Management
    public function beginTransaction(): bool {
        return $this->transaction->beginTransaction();
    }

    public function commit(): bool {
        return $this->transaction->commit();
    }

    public function rollBack(): bool {
        return $this->transaction->rollBack();
    }

    // Static method to get a connection from the pool
    public static function getInstance($dsn, $username = null, $password = null, $options = null) {
        if (self::$instance === null) {
            self::$instance = ConnectionPool::getConnection($dsn, $username, $password, $options);
        }
        return self::$instance;
    }

    // Getter for CRUDOperations
    public function getCrud() {
        return $this->crud;
    }

    // Setter for CRUDOperations
    public function setCrud($crud) {
        $this->crud = $crud;
    }

    // Destructor to release the connection
    public function __destruct() {
        ConnectionPool::releaseConnection($this);
    }
}