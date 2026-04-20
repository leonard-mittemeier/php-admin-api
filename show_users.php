<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    die("Доступ запрещен. Войдите в систему.");
}

// Проверяем роль пользователя (только 'Администратор')
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Администратор') {
    die("Доступ запрещен. У вас нет прав администратора.");
}

// Подключение к базе
try {
    $pdo = new PDO('mysql:host=localhost;dbname=php_admin;charset=utf8', 'Hao Asakura', 'DUgr@diGdoShabranigdo');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе: " . $e->getMessage());
}

// Получаем список пользователей
$stmt = $pdo->query("SELECT id, username, email, role, created_at, banned FROM users ORDER BY id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список пользователей</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        p.info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 16px;
        }
        a.logout {
            color: #ff4d4f;
            text-decoration: none;
            margin-left: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .banned {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Список пользователей (только админ)</h2>
    <p class="info">
        Вы вошли как: <strong><?=htmlspecialchars($_SESSION['username'])?></strong>
        (<a class="logout" href="logout.php">Выйти</a>)
    </p>

    <table>
        <tr>
            <th>ID</th>
            <th>Имя пользователя</th>
            <th>Email</th>
            <th>Роль</th>
            <th>Дата создания</th>
            <th>Запрещено</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?=htmlspecialchars($user['id'])?></td>
                <td><?=htmlspecialchars($user['username'])?></td>
                <td><?=htmlspecialchars($user['email'])?></td>
                <td><?=htmlspecialchars($user['role'])?></td>
                <td><?=htmlspecialchars($user['created_at'])?></td>
                <td class="<?=($user['banned'] ? 'banned' : '')?>">
                    <?=($user['banned'] ? 'Да' : 'Нет')?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>