<?php
$config = require __DIR__ . '/config/config.php';

$dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset={$config['charset']}";
$pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], $config['options']);

$stmt = $pdo->query("SELECT id, username, email, role, created_at FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Users List</title>
    <style>
        body { font-family: Arial; background:#f5f5f5; padding:40px; }
        table { border-collapse: collapse; width: 100%; background:white; }
        th, td { padding:10px; border:1px solid #ddd; text-align:left; }
        th { background:#333; color:white; }
        tr:nth-child(even) { background:#f2f2f2; }
    </style>
</head>
<body>
<h2>Users</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Created</th>
    </tr>

    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= htmlspecialchars($user['id']) ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['role']) ?></td>
        <td><?= htmlspecialchars($user['created_at']) ?></td>
    </tr>
    <?php endforeach; ?>

</table>

</body>
</html>