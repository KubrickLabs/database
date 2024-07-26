<?php

namespace app\database;

use PDO;
use PDOException;

/**
 * Class Database
 * 
 * Extends PDO to provide additional functionality such as CRUD operations
 * and transaction management, along with connection pooling.
 */
class Database extends PDO {
    private static $instance = null;
    private $crud;
    private $transaction;

    /**
     * Database constructor.
     * 
     * @param string $dsn Data Source Name
     * @param string|null $username Database username
     * @param string|null $password Database password
     * @param array|null $options PDO options
     */
    private function __construct($dsn, $username = null, $password = null, $options = null) {
        parent::__construct($dsn, $username, $password, $options);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->crud = new CRUDOperations($this);
        $this->transaction = new TransactionManager($this);
    }

    /**
     * Get the singleton instance of Database.
     * 
     * @param string $dsn Data Source Name
     * @param string|null $username Database username
     * @param string|null $password Database password
     * @param array|null $options PDO options
     * @return Database
     */
    public static function getInstance($dsn, $username = null, $password = null, $options = null) {
        if (self::$instance === null) {
            self::$instance = ConnectionPool::getConnection($dsn, $username, $password, $options);
        }
        return self::$instance;
    }

    /**
     * Release the connection back to the pool on destruction.
     */
    public function __destruct() {
        ConnectionPool::releaseConnection($this);
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
}
