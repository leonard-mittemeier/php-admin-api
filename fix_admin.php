<?php

$pdo = new PDO("mysql:host=localhost;dbname=php_admin;charset=utf8","Hao Asakura","DUgr@diGdoShabranigdo");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$hash = password_hash("123456", PASSWORD_DEFAULT);

$sql = "UPDATE users SET password=? WHERE email=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$hash,"likoishepard@gmail.com"]);

echo "Пароль администратора изменен на: 1067";

?>