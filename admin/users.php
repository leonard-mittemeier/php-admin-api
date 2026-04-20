<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
$db = (new Database())->getConnection();

// Получаем всех пользователей
$stmt = $db->query("SELECT id, username, email, role, banned, created_at FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Управление пользователями</title>
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
        .admin-badge { background: #007bff; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px; }
        button { padding: 5px 10px; margin: 2px; border: none; border-radius: 3px; cursor: pointer; }
        .ban-btn { background: #ffc107; }
        .unban-btn { background: #28a745; color: white; }
        .delete-btn { background: #dc3545; color: white; }
        .make-admin-btn { background: #6f42c1; color: white; }
    </style>
</head>
<body>
<div class="container">
    <h1>👥 Управление пользователями</h1>
    <div class="nav">
        <a href="dashboard.php">Главная</a>
        <a href="users.php">Пользователи</a>
        <a href="../api/users.php">API</a>
        <a href="logout.php">Выйти</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Статус</th>
                <th>Дата регистрации</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <?php if ($user['role'] === 'admin'): ?>
                        <span class="admin-badge">admin</span>
                    <?php else: ?>
                        user
                    <?php endif; ?>
                </td>
                <td class="<?= $user['banned'] ? 'banned' : 'active' ?>">
                    <?= $user['banned'] ? 'Забанен' : 'Активен' ?>
                </td>
                <td><?= $user['created_at'] ?></td>
                <td>
                    <?php if ($user['banned']): ?>
                        <form action="unban.php" method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" class="unban-btn">✅ Разбанить</button>
                        </form>
                    <?php else: ?>
                        <form action="ban.php" method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" class="ban-btn">⛔ Забанить</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($user['role'] !== 'admin'): ?>
                        <form action="make_admin.php" method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" class="make-admin-btn">👑 Сделать админом</button>
                        </form>
                    <?php endif; ?>

                    <form action="delete.php" method="POST" style="display:inline;" onsubmit="return confirm('Точно удалить пользователя?');">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit" class="delete-btn">❌ Удалить</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>