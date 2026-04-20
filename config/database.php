<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'php_admin';
    private $username = 'Hao Asakura';      // или ''
    private $password = 'DUgr@diGdoShabranigdo';           // если есть пароль, укажи
    private $conn;

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("DB connection error: " . $e->getMessage());
            die("Ошибка подключения к БД");
        }

        return $this->conn;
    }
}