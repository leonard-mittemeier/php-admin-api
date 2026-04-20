<?php
// --- Настройки MySQL ---
$host = 'localhost';
$db   = 'php_admin'; 
$user = 'Hao Asakura';
$pass = 'DUgr@diGdoShabranigdo'; 
$charset = 'utf8mb4';

// --- Настройки Telegram ---
$telegramToken = '8503275256:AAEmAQpV_L-HJxKgwgdID0W8qDawWJWJTjI';
$chatId = '8530959160'; // твой личный chat_id

$message = "";
$error = false;

// Создаём PDO соединение
try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// --- Обработка формы ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!$username || !$email || !$password || !$confirm) {
        $message = "Пожалуйста, заполните все поля.";
        $error = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Неверный формат email.";
        $error = true;
    } elseif ($password !== $confirm) {
        $message = "Пароли не совпадают.";
        $error = true;
    } else {
        // Хэшируем пароль
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Сохраняем в БД
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$username, $email, $hash]);

        // --- Отправка уведомления в Telegram ---
        $text = "🚀 Новый пользователь создан!\nИмя: $username\nEmail: $email";
        file_get_contents("https://api.telegram.org/bot$telegramToken/sendMessage?chat_id=$chatId&text=" . urlencode($text));

        $message = "Пользователь <strong>$username</strong> успешно создан!";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Создать пользователя</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
form {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    width: 350px;
}
form h2 {
    text-align: center;
    margin-bottom: 20px;
}
form input {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 5px;
    border: 1px solid #ccc;
}
form button {
    width: 100%;
    padding: 10px;
    margin-top: 15px;
    background: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}
form button:hover {
    background: #45a049;
}
.message {
    text-align: center;
    margin-bottom: 15px;
    color: red;
}
.message.success {
    color: green;
}
</style>
</head>
<body>

<form method="POST">
    <h2>Создать пользователя</h2>
    <?php if($message): ?>
        <div class="message <?= $error ? '' : 'success' ?>"><?= $message ?></div>
    <?php endif; ?>
    <input type="text" name="username" placeholder="Имя пользователя" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <input type="password" name="confirm" placeholder="Повторите пароль" required>
    <button type="submit">Создать</button>
</form>

</body>
</html>