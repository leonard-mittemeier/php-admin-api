<?php
require_once __DIR__ . '/core/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pdo = Database::connect();

    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // хэшируем пароль

    $stmt = $pdo->prepare(
        "INSERT INTO users (username, email, role, password) VALUES (?, ?, ?, ?)"
    );

    $stmt->execute([$username, $email, $role, $password]);

    header("Location: api/users.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Добавить пользователя</title>
<style>
body {
    background:#0f172a;
    color:white;
    font-family:Arial, sans-serif;
}

.container {
    width:400px;
    margin:auto;
    margin-top:80px;
    background:#1e293b;
    padding:20px;
    border-radius:10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.5);
}

h2 {
    text-align:center;
    margin-bottom:20px;
}

label {
    display:block;
    margin-bottom:5px;
    margin-top:15px;
}

input {
    width:100%;
    padding:8px;
    border-radius:5px;
    border:none;
    margin-bottom:5px;
}

button {
    width:100%;
    padding:10px;
    border:none;
    border-radius:5px;
    background:#3b82f6;
    color:white;
    font-weight:bold;
    cursor:pointer;
    margin-top:15px;
}

button:hover {
    background:#2563eb;
}
</style>
</head>
<body>

<div class="container">
<h2>Добавить пользователя</h2>

<form method="POST">

<label>Имя</label>
<input name="username" placeholder="Введите имя" required>

<label>Email</label>
<input name="email" type="email" placeholder="Введите email" required>

<label>Роль</label>
<input name="role" value="user" required>

<label>Пароль</label>
<input name="password" type="password" placeholder="Введите пароль" required>

<button type="submit">Добавить</button>

</form>
</div>

</body>
</html>