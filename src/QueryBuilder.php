<?php

namespace app\database;

class QueryBuilder {
    public function buildInsert($table, $data) {
        $fields = implode(", ", array_map('self::quoteIdentifier', array_keys($data)));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        return "INSERT INTO $table ($fields) VALUES ($placeholders)";
    }

    public function buildUpdate($table, $data, $where) {
        $set = implode(", ", array_map(fn($key) => self::quoteIdentifier($key) . " = ?", array_keys($data)));
        $whereClause = implode(" AND ", array_map(fn($key) => self::quoteIdentifier($key) . " = ?", array_keys($where)));
        return "UPDATE $table SET $set WHERE $whereClause";
    }

    public function buildDelete($table, $where) {
        $whereClause = implode(" AND ", array_map(fn($key) => self::quoteIdentifier($key) . " = ?", array_keys($where)));
        return "DELETE FROM $table WHERE $whereClause";
    }

    private static function quoteIdentifier($identifier) {
        return "`" . str_replace("`", "``", $identifier) . "`";
    }

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
