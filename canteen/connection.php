<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__.'/../api/classes/database.class.php';

$pdo = new Database();

?>
