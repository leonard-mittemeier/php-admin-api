<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Админ-панель</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 40px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 30px; border-radius: 10px; }
        h1 { margin-bottom: 20px; }
        .nav { margin-bottom: 30px; }
        .nav a { margin-right: 20px; text-decoration: none; color: #007bff; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .banned { color: red; font-weight: bold; }
        .active { color: green; }
        button { padding: 5px 10px; margin: 2px; border: none; border-radius: 3px; cursor: pointer; }
        .ban { background: #ffc107; }
        .unban { background: #28a745; color: white; }
        .delete { background: #dc3545; color: white; }
    </style>
</head>
<body>
<div class="container">
    <h1>👑 Админ-панель</h1>
    <div class="nav">
        <a href="dashboard.php">Главная</a>
        <a href="users.php">Пользователи</a>
        <a href="../api/users.php">API</a>
        <a href="logout.php">Выйти</a>
    </div>

    <h2>Добро пожаловать, админ!</h2>
    <p>Используй меню для управления.</p>
</div>
</body>
</html>