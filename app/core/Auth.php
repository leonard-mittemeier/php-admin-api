<?php
require_once __DIR__ . '/Database.php';

class Auth {

    public static function login($username, $password) {
        $pdo = Database::connect();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = $user;
            return true;
        }

        return false;
    }

    public static function check() {
        return isset($_SESSION['user']);
    }

    public static function logout() {
        session_destroy();
    }
}