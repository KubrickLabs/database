<?php

namespace app\database;

/**
 * Class QueryBuilder
 * 
 * Builds SQL queries for various operations.
 */
class QueryBuilder {
    /**
     * Build an INSERT query.
     * 
     * @param string $table Table name
     * @param array $data Associative array of columns and values
     * @return string SQL query
     */
    public function buildInsert($table, $data) {
        $fields = implode(", ", array_map('self::quoteIdentifier', array_keys($data)));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        return "INSERT INTO $table ($fields) VALUES ($placeholders)";
    }

    /**
     * Build an UPDATE query.
     * 
     * @param string $table Table name
     * @param array $data Associative array of columns and values to update
     * @param array $where Associative array for WHERE clause
     * @return string SQL query
     */
    public function buildUpdate($table, $data, $where) {
        $set = implode(", ", array_map(fn($key) => self::quoteIdentifier($key) . " = ?", array_keys($data)));
        $whereClause = implode(" AND ", array_map(fn($key) => self::quoteIdentifier($key) . " = ?", array_keys($where)));
        return "UPDATE $table SET $set WHERE $whereClause";
    }

    /**
     * Build a DELETE query.
     * 
     * @param string $table Table name
     * @param array $where Associative array for WHERE clause
     * @return string SQL query
     */
    public function buildDelete($table, $where) {
        $whereClause = implode(" AND ", array_map(fn($key) => self::quoteIdentifier($key) . " = ?", array_keys($where)));
        return "DELETE FROM $table WHERE $whereClause";
    }

    /**
     * Quote SQL identifiers.
     * 
     * @param string $identifier Column or table name
     * @return string Quoted identifier
     */
    private static function quoteIdentifier($identifier) {
        return "`" . str_replace("`", "``", $identifier) . "`";
    }

    /**
     * Build a SELECT query.
     * 
     * @param string $table Table name
     * @param array $columns Array of columns to select
     * @param array $where Associative array for WHERE clause
     * @param string $orderBy Column to sort results by
     * @return string SQL query
     */
    public function buildSelect($table, $columns = ['*'], $where = [], $orderBy = '') {
        $fields = implode(", ", array_map('self::quoteIdentifier', $columns));
        $whereClause = '';
        if (!empty($where)) {
            $whereClause = 'WHERE ' . implode(" AND ", array_map(fn($key) => self::quoteIdentifier($key) . " = ?", array_keys($where)));
        }
        $orderByClause = $orderBy ? "ORDER BY " . self::quoteIdentifier($orderBy) : '';
        return "SELECT $fields FROM $table $whereClause $orderByClause";
    }
}
