<?php

use app\database\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase {
    private $queryBuilder;

    protected function setUp(): void {
        $this->queryBuilder = new QueryBuilder();
    }

    public function testBuildInsert() {
        $query = $this->queryBuilder->buildInsert('users', ['name' => 'John Doe', 'email' => 'john@example.com']);
        $expected = "INSERT INTO `users` (`name`, `email`) VALUES (?, ?)";
        $this->assertEquals($expected, $query);
    }

    public function testBuildUpdate() {
        $query = $this->queryBuilder->buildUpdate('users', ['name' => 'Jane Doe'], ['id' => 1]);
        $expected = "UPDATE `users` SET `name` = ? WHERE `id` = ?";
        $this->assertEquals($expected, $query);
    }

    public function testBuildDelete() {
        $query = $this->queryBuilder->buildDelete('users', ['id' => 1]);
        $expected = "DELETE FROM `users` WHERE `id` = ?";
        $this->assertEquals($expected, $query);
    }
}
