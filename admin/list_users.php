<?php
// Параметры подключения
$host = 'localhost';
$db   = 'php_admin';
$user = 'Hao Asakura';
$pass = 'DUgr@diGdoShabranigdo';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Обработка бан/разбан
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ban_id'])) {
        $stmt = $pdo->prepare("UPDATE users SET banned = 1 WHERE id = ?");
        $stmt->execute([$_POST['ban_id']]);
    }
    if (isset($_POST['unban_id'])) {
        $stmt = $pdo->prepare("UPDATE users SET banned = 0 WHERE id = ?");
        $stmt->execute([$_POST['unban_id']]);
    }
}

// Получаем всех пользователей
$stmt = $pdo->query("SELECT id, username, email, created_at, banned FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Список пользователей</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
    min-height: 100vh;
    padding: 20px;
    margin: 0;
}
h2 {
    text-align: center;
    color: #333;
}
table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-radius: 10px;
    overflow: hidden;
}
th, td {
    padding: 12px 15px;
    text-align: center;
}
th {
    background-color: #007BFF;
    color: white;
}
tr:nth-child(even) {
    background-color: #f7f9fc;
}
button {
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    color: white;
}
button.ban {
    background-color: #FF4B5C;
}
button.ban:hover {
    background-color: #e63946;
}
button.unban {
    background-color: #28a745;
}
button.unban:hover {
    background-color: #218838;
}
</style>
</head>
<body>
<h2>Список пользователей</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Имя</th>
        <th>Email</th>
        <th>Дата создания</th>
        <th>Статус</th>
        <th>Действие</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= htmlspecialchars($user['id']) ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['created_at']) ?></td>
        <td><?= $user['banned'] ? 'Забанен' : 'Активен' ?></td>
        <td>
            <form method="POST" style="display:inline;">
                <?php if (!$user['banned']): ?>
                    <button class="ban" name="ban_id" value="<?= $user['id'] ?>">Бан</button>
                <?php else: ?>
                    <button class="unban" name="unban_id" value="<?= $user['id'] ?>">Разбанинть</button>
                <?php endif; ?>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>