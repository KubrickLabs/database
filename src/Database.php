<?php

namespace app\database;

use PDO;
use PDOException;

class Database extends PDO {
    private static $instance = null;
    private $crud;
    private $transaction;

    // Dependency Injection via Constructor
    private function __construct(CRUDOperations $crud, TransactionManager $transaction, $dsn, $username = null, $password = null, $options = null) {
        parent::__construct($dsn, $username, $password, $options);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->crud = $crud;
        $this->transaction = $transaction;
    }

    // CRUD Operations
    public function select($query, $params = [], $fetchMode = PDO::FETCH_ASSOC) {
        return $this->crud->select($query, $params, $fetchMode);
    }

    public function insert($table, $data) {
        return $this->crud->insert($table, $data);
    }

    public function update($table, $data, $where) {
        return $this->crud->update($table, $data, $where);
    }

    public function delete($table, $where) {
        return $this->crud->delete($table, $where);
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
    public static function getInstance($dsn, $username = null, $password = null, $options = null): self {
        if (self::$instance === null) {
            // Create a new PDO instance
            $pdo = new PDO($dsn, $username, $password, $options);
            $crud = new CRUDOperations($pdo);
            $transaction = new TransactionManager($pdo);
            self::$instance = new self($crud, $transaction, $dsn, $username, $password, $options);
        }
        return self::$instance;
    }

    // Destructor to release the connection
    public function __destruct() {
        ConnectionPool::releaseConnection($this);
    }
}
