<?php
require_once __DIR__.'/../env.php';
class Database extends PDO {
    public function __construct() {
        parent::__construct('mysql:host=localhost;dbname=canteen;charset=utf8mb4', 'root', Env::DB_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ]);

        $this->exec('SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci');
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
}