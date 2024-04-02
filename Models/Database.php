<?php 
require_once __DIR__.'/../inc/Config.php';

class Database {
    public function getConnection () {
        try {
            $pdo = new PDO("mysql:dbname=". $GLOBALS['db_name']. ";host:" . $GLOBALS['host'], $GLOBALS['db_user'], $GLOBALS['db_pass']);
            return $pdo;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }
}