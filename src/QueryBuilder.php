<?php

namespace app\database;

class QueryBuilder {
    /**
     * Builds an INSERT SQL query.
     *
     * @param string $table The name of the table.
     * @param array $data Associative array of column-value pairs.
     * @return string The generated SQL query.
     * @throws InvalidArgumentException if table name or data is empty.
     */
    public function buildInsert($table, $data) {
        if (empty($table) || empty($data)) {
            throw new \InvalidArgumentException("Table name and data cannot be empty.");
        }
        $fields = implode(", ", array_map('self::quoteIdentifier', array_keys($data)));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        return "INSERT INTO $table ($fields) VALUES ($placeholders)";
    }

    /**
     * Builds an UPDATE SQL query.
     *
     * @param string $table The name of the table.
     * @param array $data Associative array of column-value pairs for updating.
     * @param array $where Associative array of column-value pairs for the WHERE clause.
     * @return string The generated SQL query.
     * @throws InvalidArgumentException if table name, data, or where clause is empty.
     */
    public function buildUpdate($table, $data, $where) {
        if (empty($table) || empty($data) || empty($where)) {
            throw new \InvalidArgumentException("Table name, data, and where clause cannot be empty.");
        }
        $set = implode(", ", array_map(fn($key) => self::quoteIdentifier($key) . " = ?", array_keys($data)));
        $whereClause = implode(" AND ", array_map(fn($key) => self::quoteIdentifier($key) . " = ?", array_keys($where)));
        return "UPDATE $table SET $set WHERE $whereClause";
    }

    /**
     * Builds a DELETE SQL query.
     *
     * @param string $table The name of the table.
     * @param array $where Associative array of column-value pairs for the WHERE clause.
     * @return string The generated SQL query.
     * @throws InvalidArgumentException if table name or where clause is empty.
     */
    public function buildDelete($table, $where) {
        if (empty($table) || empty($where)) {
            throw new \InvalidArgumentException("Table name and where clause cannot be empty.");
        }
        $whereClause = implode(" AND ", array_map(fn($key) => self::quoteIdentifier($key) . " = ?", array_keys($where)));
        return "DELETE FROM $table WHERE $whereClause";
    }

    /**
     * Quotes an identifier (e.g., column or table name) to prevent SQL injection.
     *
     * @param string $identifier The identifier to quote.
     * @return string The quoted identifier.
     */
    private static function quoteIdentifier($identifier) {
        return "`" . str_replace("`", "``", $identifier) . "`";
    }

    /**
     * Builds a SELECT SQL query.
     *
     * @param string $table The name of the table.
     * @param array $columns List of columns to select.
     * @param array $where Associative array of column-value pairs for the WHERE clause.
     * @param string $orderBy Column name to order by.
     * @param int|null $limit Number of rows to limit the result to.
     * @param int|null $offset Number of rows to skip before starting to return rows.
     * @return string The generated SQL query.
     */
    public function buildSelect($table, $columns = ['*'], $where = [], $orderBy = '', $limit = null, $offset = null) {
        $fields = implode(", ", array_map('self::quoteIdentifier', $columns));
        $whereClause = '';
        if (!empty($where)) {
            $whereClause = 'WHERE ' . implode(" AND ", array_map(fn($key) => self::quoteIdentifier($key) . " = ?", array_keys($where)));
        }
        $orderByClause = $orderBy ? "ORDER BY " . self::quoteIdentifier($orderBy) : '';
        $limitClause = $limit ? "LIMIT $limit" : '';
        $offsetClause = $offset ? "OFFSET $offset" : '';
        return "SELECT $fields FROM $table $whereClause $orderByClause $limitClause $offsetClause";
    }
}