<?php

namespace app\database;

use PDO;
use PDOException;

/**
 * Class CRUDOperations
 * 
 * Handles Create, Read, Update, and Delete operations.
 */
class CRUDOperations {
    private $connection;
    private $queryBuilder;

    /**
     * CRUDOperations constructor.
     * 
     * @param PDO $connection
     */
    public function __construct(PDO $connection) {
        $this->connection = $connection;
        $this->queryBuilder = new QueryBuilder();
    }

    /**
     * Execute a SELECT query.
     * 
     * @param string $query SQL query
     * @param array $params Parameters to bind
     * @param int $fetchMode Fetch mode for PDO
     * @return array|false Results or false on failure
     */
    public function select($query, $params = [], $fetchMode = PDO::FETCH_ASSOC) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll($fetchMode);
        } catch (PDOException $e) {
            ErrorHandler::handleError($e);
            return false;
        }
    }

    /**
     * Execute an INSERT query.
     * 
     * @param string $table Table name
     * @param array $data Associative array of columns and values
     * @return bool Success status
     */
    public function insert($table, $data) {
        try {
            $query = $this->queryBuilder->buildInsert($table, $data);
            $stmt = $this->connection->prepare($query);
            return $stmt->execute(array_values($data));
        } catch (PDOException $e) {
            ErrorHandler::handleError($e);
            return false;
        }
    }

    /**
     * Execute an UPDATE query.
     * 
     * @param string $table Table name
     * @param array $data Associative array of columns and values to update
     * @param array $where Associative array for WHERE clause
     * @return bool Success status
     */
    public function update($table, $data, $where) {
        try {
            $query = $this->queryBuilder->buildUpdate($table, $data, $where);
            $stmt = $this->connection->prepare($query);
            return $stmt->execute(array_merge(array_values($data), array_values($where)));
        } catch (PDOException $e) {
            ErrorHandler::handleError($e);
            return false;
        }
    }

    /**
     * Execute a DELETE query.
     * 
     * @param string $table Table name
     * @param array $where Associative array for WHERE clause
     * @return bool Success status
     */
    public function delete($table, $where) {
        try {
            $query = $this->queryBuilder->buildDelete($table, $where);
            $stmt = $this->connection->prepare($query);
            return $stmt->execute(array_values($where));
        } catch (PDOException $e) {
            ErrorHandler::handleError($e);
            return false;
        }
    }
}