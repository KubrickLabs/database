<?php

namespace app\database;

use PDO;
use PDOException;

/**
 * Class Database
 *
 * Extends PDO to provide additional functionality such as CRUD operations and transaction management.
 *
 * @package app\database
 */
class Database extends PDO
{
    /**
     * @var Database|null Singleton instance of the Database class.
     */
    private static $instance = null;

    /**
     * @var CRUDOperations Instance of CRUDOperations for database operations.
     */
    private $crud;

    /**
     * @var TransactionManager Instance of TransactionManager for transaction management.
     */
    private $transaction;

    /**
     * Database constructor.
     *
     * @param string $dsn The Data Source Name, or DSN, contains the information required to connect to the database.
     * @param string|null $username The user name for the DSN string. This parameter is optional for some PDO drivers.
     * @param string|null $password The password for the DSN string. This parameter is optional for some PDO drivers.
     * @param array|null $options A key=>value array of driver-specific connection options.
     */
    public function __construct($dsn, $username = null, $password = null, $options = null)
    {
        parent::__construct($dsn, $username, $password, $options);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->crud = new CRUDOperations($this);
        $this->transaction = new TransactionManager($this);
    }

    /**
     * Begins a transaction.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function beginTransaction(): bool
    {
        return $this->transaction->beginTransaction();
    }

    /**
     * Commits a transaction.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function commit(): bool
    {
        return $this->transaction->commit();
    }

    /**
     * Rolls back a transaction.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function rollBack(): bool
    {
        return $this->transaction->rollBack();
    }

    /**
     * Gets the singleton instance of the Database class.
     *
     * @param string $dsn The Data Source Name, or DSN, contains the information required to connect to the database.
     * @param string|null $username The user name for the DSN string. This parameter is optional for some PDO drivers.
     * @param string|null $password The password for the DSN string. This parameter is optional for some PDO drivers.
     * @param array|null $options A key=>value array of driver-specific connection options.
     * @return Database|null Returns the singleton instance of the Database class.
     */
    public static function getInstance($dsn, $username = null, $password = null, $options = null)
    {
        if (self::$instance === null) {
            self::$instance = ConnectionPool::getConnection($dsn, $username, $password, $options);
        }
        return self::$instance;
    }

    /**
     * Gets the CRUDOperations instance.
     *
     * @return CRUDOperations Returns the CRUDOperations instance.
     */
    public function getCrud()
    {
        return $this->crud;
    }

    /**
     * Sets the CRUDOperations instance.
     *
     * @param CRUDOperations $crud The CRUDOperations instance.
     */
    public function setCrud($crud)
    {
        $this->crud = $crud;
    }

    /**
     * Destructor to release the connection.
     */
    public function __destruct()
    {
        ConnectionPool::releaseConnection($this);
    }
}