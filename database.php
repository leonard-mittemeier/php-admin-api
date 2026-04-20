<?php
class Database {
    public static function connect() {
        $cfg = require __DIR__ . '/../config/config.php';
        $dsn = "mysql:host={$cfg['db_host']};dbname={$cfg['db_name']};charset={$cfg['charset']}";
        return new PDO($dsn, $cfg['db_user'], $cfg['db_pass'], $cfg['options']);
    }
}