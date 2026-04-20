<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: forgot.php");
    exit;
}

$email = trim($_POST['email'] ?? '');

$db = (new Database())->getConnection();

// Проверяем, есть ли пользователь
$stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: forgot.php?message=" . urlencode("Email не найден"));
    exit;
}

// Генерируем токен
$token = bin2hex(random_bytes(32));
$expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Сохраняем в БД
$stmt = $db->prepare("INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)");
$stmt->execute([$email, $token, $expires]);

// Отправляем ссылку на email
$resetLink = "http://localhost/php-admin/public/reset.php?token=$token";

$mailer = new Mailer('yandex');
$body = "<h2>Восстановление пароля</h2>
         <p>Перейдите по ссылке для сброса пароля:</p>
         <p><a href='$resetLink'>$resetLink</a></p>
         <p>Ссылка действительна 1 час.</p>";

if (!$mailer->send($email, 'Восстановление пароля', $body)) {
    header("Location: forgot.php?message=" . urlencode("Ошибка отправки"));
    exit;
}

header("Location: forgot.php?message=" . urlencode("Ссылка отправлена на email"));
exit;