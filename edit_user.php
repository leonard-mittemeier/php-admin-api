<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Администратор') {
    die("Доступ запрещен");
}

$pdo = new PDO(
    "mysql:host=localhost;dbname=php_admin;charset=utf8",
    "Hao Asakura",
    "DUgr@diGdoShabranigdo"
);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Пользователь не найден");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $banned = $_POST['banned'];

    $stmt = $pdo->prepare(
        "UPDATE users SET username=?, email=?, role=?, banned=? WHERE id=?"
    );

    $stmt->execute([$username,$email,$role,$banned,$id]);

    header("Location: admin_panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Редактировать пользователя</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h2>Редактирование пользователя</h2>

<form method="post">

<label>Имя</label>
<input type="text" name="username" value="<?=htmlspecialchars($user['username'])?>">

<label>Email</label>
<input type="email" name="email" value="<?=htmlspecialchars($user['email'])?>">

<label>Роль</label>
<select name="role">
<option value="user" <?= $user['role']=="user"?"selected":"" ?>>User</option>
<option value="Администратор" <?= $user['role']=="Администратор"?"selected":"" ?>>Администратор</option>
</select>

<label>Бан</label>
<select name="banned">
<option value="0" <?= $user['banned']==0?"selected":"" ?>>Нет</option>
<option value="1" <?= $user['banned']==1?"selected":"" ?>>Да</option>
</select>

<br><br>

<button type="submit">Сохранить</button>

</form>

</div>

</body>
</html>