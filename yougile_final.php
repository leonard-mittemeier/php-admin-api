<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "=== ТЕСТ YOUGILE ===\n\n";

$login = 'likoishepard@gmail.com';
$password = 'NormandySR2';

echo "Логин: $login\n";
echo "Пароль: " . str_repeat('*', strlen($password)) . "\n\n";

// Проверяем cURL
if (!function_exists('curl_init')) {
    die("cURL не установлен!");
}
echo "cURL OK\n\n";

// Выполняем запрос
$ch = curl_init('https://yougile.com/api-v2/auth/companies');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'login' => $login,
    'password' => $password
]));
curl_setopt($ch, CURLOPT_SSL_OPTIONS, CURLSSLOPT_NATIVE_CA);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

echo "HTTP код: " . $httpCode . "\n";
if ($error) {
    echo "Ошибка cURL: " . $error . "\n";
}
echo "Ответ (сырой):\n" . $response . "\n";
echo "\n=== КОНЕЦ ТЕСТА ===\n";
?>