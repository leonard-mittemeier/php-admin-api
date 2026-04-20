<?php
$telegramToken = '8503275256:AAEmAQpV_L-HJxKgwgdID0W8qDawWJWJTjI';   
$telegramChatId = '8530959160';                                    
$message = "";
$error = false;

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
		
        // TODO: Здесь будет вставка в БД
        /*
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
        */

        $message = "Пользователь <strong>$username</strong> успешно создан!";

        // --- Отправка уведомления в Telegram ---
        $text = "🆕 Новый пользователь зарегистрирован:\nИмя: $username\nEmail: $email";
        
        // Формируем URL с твоими данными
        $url = "https://api.telegram.org/bot$telegramToken/sendMessage?chat_id=$telegramChatId&text=" . urlencode($text);

        // Отправляем запрос
        $response = @file_get_contents($url);

        if ($response === FALSE) {
            $message .= " ⚠️ Не удалось отправить уведомление в Telegram.";
        } else {
            $message .= " ✅ Уведомление отправлено в Telegram.";
        }
    }
}
?>

<!-- Форма регистрации (пример) -->
<form method="POST">
    <input type="text" name="username" placeholder="Имя" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <input type="password" name="confirm" placeholder="Подтвердите пароль" required>
    <button type="submit">Зарегистрироваться</button>
</form>

<?php if ($message): ?>
    <div style="margin-top:20px; padding:10px; background:#f0f0f0;">
        <?= $message ?>
    </div>
<?php endif; ?>