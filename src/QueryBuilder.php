<?php

namespace app\core\database;

class QueryBuilder {
    public function buildInsert($table, $data) {
        $fields = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        return "INSERT INTO $table ($fields) VALUES ($placeholders)";
    }

    public function buildUpdate($table, $data, $where) {
        $set = implode(", ", array_map(fn($key) => "$key = ?", array_keys($data)));
        $whereClause = implode(" AND ", array_map(fn($key) => "$key = ?", array_keys($where)));
        return "UPDATE $table SET $set WHERE $whereClause";
    }

    public function buildDelete($table, $where) {
        $whereClause = implode(" AND ", array_map(fn($key) => "$key = ?", array_keys($where)));
        return "DELETE FROM $table WHERE $whereClause";
    }
}
