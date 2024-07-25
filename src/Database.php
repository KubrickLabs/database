<?php

namespace app\core\database;

use PDO;
use PDOException;

class Database extends PDO {
    private $crud;
    private $transaction;

    private function __construct($dsn, $username = null, $password = null, $options = null) {
        parent::__construct($dsn, $username, $password, $options);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->crud = new CRUDOperations($this);
        $this->transaction = new TransactionManager($this);
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
    public static function getInstance($dsn, $username = null, $password = null, $options = null) {
        $connection = ConnectionPool::getConnection($dsn, $username, $password, $options);
        return new self($dsn, $username, $password, $options);
    }

    // Destructor to release the connection
    public function __destruct() {
        ConnectionPool::releaseConnection($this);
    }
}
