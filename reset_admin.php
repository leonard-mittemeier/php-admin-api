<?php
// Соединение с базой
$pdo = new PDO('mysql:host=localhost;dbname=php_admin;charset=utf8', 'Hao Asakura', 'DUgr@diGdoShabranigdo');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Новый пароль для админа
$new_password = password_hash("1067", PASSWORD_DEFAULT);

// Обновляем пароль для Хао Асакура
$pdo->exec("UPDATE users SET password = '$new_password' WHERE email = 'likoishepard@gmail.com'");

echo "Пароль админа успешно сброшен на 1067!";