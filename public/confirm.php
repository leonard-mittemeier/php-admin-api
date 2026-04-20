<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$email = $_POST['email'] ?? '';
$code = trim($_POST['code'] ?? '');

// Проверяем, есть ли временные данные
if (!isset($_SESSION['temp_user']) || $_SESSION['temp_user']['email'] !== $email) {
    header("Location: index.php?message=" . urlencode("Ошибка подтверждения"));
    exit;
}

$temp = $_SESSION['temp_user'];

// Проверяем код
if ($temp['code'] != $code) {
    header("Location: verify.php?email=" . urlencode($email) . "&message=" . urlencode("Неверный код"));
    exit;
}

// TODO: Сохраняем пользователя в БД
/*
require_once __DIR__ . '/../config/database.php';
$database = new Database();
$pdo = $database->getConnection();
$stmt = $pdo->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
$stmt->execute([$temp['username'], $temp['email'], $temp['password']]);
$userId = $pdo->lastInsertId();
*/

// Для примера — просто авторизуем
$_SESSION['user_id'] = 1;
$_SESSION['username'] = $temp['username'];

// Очищаем временные данные
unset($_SESSION['temp_user']);

// Отправляем финальное уведомление в Telegram
$telegram = new Telegram($telegramToken, $telegramChatId);
$telegram->sendMessage("✅ Пользователь подтвердил email и зарегистрирован:\nИмя: {$temp['username']}\nEmail: {$temp['email']}");

// Перенаправляем в личный кабинет
header("Location: dashboard.php");
exit;