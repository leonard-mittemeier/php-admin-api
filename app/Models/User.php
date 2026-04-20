<?php
require_once __DIR__ . '/../../config/database.php';

class User
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Получить всех пользователей
     */
    public function all()
    {
        $stmt = $this->db->query("SELECT id, username, email, role, created_at, banned FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Найти пользователя по ID
     */
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT id, username, email, role, created_at, banned FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Создать нового пользователя
     */
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['username'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'] ?? 'user'
        ]);
    }

    /**
     * Обновить данные пользователя
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        return $stmt->execute([$data['username'], $data['email'], $data['role'], $id]);
    }

    /**
     * Удалить пользователя
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}