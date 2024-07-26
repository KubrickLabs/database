<?php

use app\database\TransactionManager;
use PHPUnit\Framework\TestCase;

class TransactionManagerTest extends TestCase {
    private $transactionManager;

    protected function setUp(): void {
        $dsn = 'mysql:host=localhost;dbname=testdb';
        $username = 'root';
        $password = '';
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        $pdo = new PDO($dsn, $username, $password, $options);
        $this->transactionManager = new TransactionManager($pdo);
    }

    public function testBeginTransaction() {
        $result = $this->transactionManager->beginTransaction();
        $this->assertTrue($result);
    }

    public function testCommit() {
        $this->transactionManager->beginTransaction();
        $result = $this->transactionManager->commit();
        $this->assertTrue($result);
    }

    public function testRollBack() {
        $this->transactionManager->beginTransaction();
        $result = $this->transactionManager->rollBack();
        $this->assertTrue($result);
    }
}
