<?php

use app\database\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase {
    private $db;

    protected function setUp(): void {
        $dsn = 'mysql:host=localhost;dbname=testdb';
        $username = 'root';
        $password = '';
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        $this->db = Database::getInstance($dsn, $username, $password, $options);
    }

    public function testSelect() {
        $result = $this->db->select('SELECT * FROM users WHERE id = ?', [1]);
        $this->assertIsArray($result);
    }

    public function testInsert() {
        $result = $this->db->insert('users', ['name' => 'John Doe', 'email' => 'john@example.com']);
        $this->assertTrue($result);
    }

    public function testUpdate() {
        $result = $this->db->update('users', ['name' => 'Jane Doe'], ['id' => 1]);
        $this->assertTrue($result);
    }

    public function testDelete() {
        $result = $this->db->delete('users', ['id' => 1]);
        $this->assertTrue($result);
    }

    public function testBeginTransaction() {
        $result = $this->db->beginTransaction();
        $this->assertTrue($result);
    }

    public function testCommit() {
        $this->db->beginTransaction();
        $result = $this->db->commit();
        $this->assertTrue($result);
    }

    public function testRollBack() {
        $this->db->beginTransaction();
        $result = $this->db->rollBack();
        $this->assertTrue($result);
    }
}
