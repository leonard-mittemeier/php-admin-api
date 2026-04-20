<?php
class Middleware {
    public static function auth() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }
    }

    public static function admin() {
        self::auth();
        if ($_SESSION['role'] !== 'admin') {
            die("Access denied");
        }
    }
}