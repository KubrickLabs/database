<?php

use app\database\CRUDOperations;
use app\database\QueryBuilder;
use PHPUnit\Framework\TestCase;

class CRUDOperationsTest extends TestCase {
    private $crud;

    protected function setUp(): void {
        $dsn = 'mysql:host=localhost;dbname=testdb';
        $username = 'root';
        $password = '';
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        $pdo = new PDO($dsn, $username, $password, $options);
        $this->crud = new CRUDOperations($pdo);
    }

    public function testSelect() {
        $result = $this->crud->select('SELECT * FROM users WHERE id = ?', [1]);
        $this->assertIsArray($result);
    }

    public function testInsert() {
        $result = $this->crud->insert('users', ['name' => 'John Doe', 'email' => 'john@example.com']);
        $this->assertTrue($result);
    }

    public function testUpdate() {
        $result = $this->crud->update('users', ['name' => 'Jane Doe'], ['id' => 1]);
        $this->assertTrue($result);
    }

    public function testDelete() {
        $result = $this->crud->delete('users', ['id' => 1]);
        $this->assertTrue($result);
    }
}
