<?php

namespace app\database;

use PDO;
use PDOException;

/**
 * Class CRUDOperations
 *
 * Provides methods for performing CRUD operations on a database.
 *
 * @package app\database
 */
class CRUDOperations
{
    /**
     * @var PDO The PDO connection instance.
     */
    private $connection;

    /**
     * @var QueryBuilder The QueryBuilder instance.
     */
    private $queryBuilder;

    /**
     * CRUDOperations constructor.
     *
     * @param PDO $connection The PDO connection instance.
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->queryBuilder = new QueryBuilder();
    }

    /**
     * Executes a SELECT query.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @param int $fetchMode The fetch mode for the results.
     * @return array|false The fetched results or false on failure.
     */
    public function select(string $query, array $params = [], int $fetchMode = PDO::FETCH_ASSOC)
    {
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
     * Executes an INSERT query.
     *
     * @param string $table The name of the table.
     * @param array $data The data to insert.
     * @return bool Success status.
     */
    public function insert(string $table, array $data): bool
    {
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
     * Executes an UPDATE query.
     *
     * @param string $table The name of the table.
     * @param array $data The data to update.
     * @param array $where The conditions for the update.
     * @return bool Success status.
     */
    public function update(string $table, array $data, array $where): bool
    {
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
     * Executes a DELETE query.
     *
     * @param string $table The name of the table.
     * @param array $where The conditions for the delete.
     * @return bool Success status.
     */
    public function delete(string $table, array $where): bool
    {
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