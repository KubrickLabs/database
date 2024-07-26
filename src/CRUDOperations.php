<?php

namespace app\database;

use PDO;
use PDOException;

class CRUDOperations {
    private $connection;
    private $queryBuilder;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
        $this->queryBuilder = new QueryBuilder();
    }

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
