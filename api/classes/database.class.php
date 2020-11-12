<?php

echo '1.1';
class Database extends PDO {
    public function __construct() {
        echo '1.2';
        parent::__construct('mysql:host=localhost;dbname=canteen;charset=utf8mb4', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ]);

        $this->exec('SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci');
    }
}