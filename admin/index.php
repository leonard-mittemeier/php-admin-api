<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$db = (new Database())->getConnection();

// =============================================
// ВЫХОД
// =============================================
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// =============================================
// ОБРАБОТКА ДЕЙСТВИЙ (ban / unban / delete / make_admin)
// =============================================
if (isset($_GET['action']) && isset($_GET['id']) && isset($_SESSION['admin_id'])) {
    $id = (int)$_GET['id'];
    
    switch ($_GET['action']) {
        case 'ban':
            $stmt = $db->prepare("UPDATE users SET banned = 1 WHERE id = ?");
            $stmt->execute([$id]);
            break;
        case 'unban':
            $stmt = $db->prepare("UPDATE users SET banned = 0 WHERE id = ?");
            $stmt->execute([$id]);
            break;
        case 'delete':
            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            break;
        case 'make_admin':
            $stmt = $db->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
            $stmt->execute([$id]);
            break;
    }
    header("Location: index.php");
    exit;
}

// =============================================
// ВХОД
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password']) && $user['role'] === 'admin') {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['username'];
    } else {
        $error = "Неверный email, пароль или недостаточно прав";
    }
}

// =============================================
// ЕСЛИ УЖЕ АВТОРИЗОВАН — ПОКАЗЫВАЕМ АДМИНКУ
// =============================================
if (isset($_SESSION['admin_id'])):

// Получаем список пользователей
$users = $db->query("SELECT id, username, email, role, banned, created_at FROM users ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header h1::before {
            content: "👑";
            font-size: 32px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-name {
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 20px;
            transition: all 0.3s;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        .content {
            padding: 30px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
        }

        .stat-card:nth-child(2) {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .stat-card:nth-child(3) {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .stat-number {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }

        .table-container {
            overflow-x: auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #dee2e6;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            color: #666;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-admin {
            background: #667eea;
            color: white;
        }

        .badge-user {
            background: #e9ecef;
            color: #495057;
        }

        .status-banned {
            color: #dc3545;
            font-weight: 600;
        }

        .status-active {
            color: #28a745;
            font-weight: 600;
        }

        .actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }

        .btn-ban {
            background: #ffc107;
            color: #000;
        }

        .btn-unban {
            background: #28a745;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-admin {
            background: #6f42c1;
            color: white;
        }

        .btn-view {
            background: #17a2b8;
            color: white;
        }

        .welcome-message {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            font-size: 18px;
        }

        .welcome-message strong {
            font-size: 22px;
            display: block;
            margin-bottom: 5px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .user-info {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Панель управления</h1>
            <div class="user-info">
                <span class="user-name"><?= htmlspecialchars($_SESSION['admin_name']) ?></span>
                <a href="?logout=1" class="logout-btn">Выйти</a>
            </div>
        </div>

        <div class="content">
            <div class="welcome-message">
                <strong>👋 С возвращением, <?= htmlspecialchars($_SESSION['admin_name']) ?>!</strong>
                <p>Управляйте пользователями вашего сайта.</p>
            </div>

            <div class="stats">
                <?php
                $total = count($users);
                $admins = $db->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
                $banned = $db->query("SELECT COUNT(*) FROM users WHERE banned = 1")->fetchColumn();
                ?>
                <div class="stat-card">
                    <div class="stat-number"><?= $total ?></div>
                    <div class="stat-label">Всего пользователей</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $admins ?></div>
                    <div class="stat-label">Администраторов</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $banned ?></div>
                    <div class="stat-label">Забанено</div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя пользователя</th>
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
                            <td>#<?= $user['id'] ?></td>
                            <td><strong><?= htmlspecialchars($user['username']) ?></strong></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <span class="badge badge-admin">Администратор</span>
                                <?php else: ?>
                                    <span class="badge badge-user">Пользователь</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user['banned']): ?>
                                    <span class="status-banned">⛔ Забанен</span>
                                <?php else: ?>
                                    <span class="status-active">✅ Активен</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></td>
                            <td>
                                <div class="actions">
                                    <?php if ($user['banned']): ?>
                                        <a href="?action=unban&id=<?= $user['id'] ?>" class="btn btn-unban">✅ Разбанить</a>
                                    <?php else: ?>
                                        <a href="?action=ban&id=<?= $user['id'] ?>" class="btn btn-ban">⛔ Забанить</a>
                                    <?php endif; ?>
                                    
                                    <?php if ($user['role'] !== 'admin'): ?>
                                        <a href="?action=make_admin&id=<?= $user['id'] ?>" class="btn btn-admin">👑 Сделать админом</a>
                                    <?php endif; ?>
                                    
                                    <a href="?action=delete&id=<?= $user['id'] ?>" class="btn btn-delete" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">❌ Удалить</a>
                                    
                                    <a href="../api/user.php?id=<?= $user['id'] ?>" class="btn btn-view" target="_blank">👁️ Просмотр</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php else: ?>

<!-- ============================================= -->
<!-- ФОРМА ВХОДА -->
<!-- ============================================= -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ-панель</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102,126,234,0.3);
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>👑 Админ-панель</h1>
                <p>Войдите для управления сайтом</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Введите email" required>
                </div>

                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" placeholder="Введите пароль" required>
                </div>

                <button type="submit" name="login" class="login-btn">Войти</button>
            </form>

            <div class="login-footer">
            </div>
        </div>
    </div>
</body>
</html>

<?php endif; ?>