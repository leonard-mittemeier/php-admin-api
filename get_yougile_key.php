<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h2>🔍 Жёсткий тест YouGile</h2>";

$login = 'likoishepard@gmail.com';
$password = 'NormandySR2';

echo "<p>📧 Логин: $login</p>";
echo "<p>🔐 Пароль: " . str_repeat('*', strlen($password)) . "</p>";

// Проверяем, есть ли cURL
if (!function_exists('curl_init')) {
    die("❌ cURL не установлен в PHP!");
}
echo "✅ cURL есть.<br>";

// Простой GET-запрос (без авторизации) — проверим, отвечает ли YouGile вообще
$ch = curl_init('https://yougile.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "<p>📡 Пинг YouGile (главная страница): код $httpCode</p>";

if ($httpCode > 0) {
    echo "✅ YouGile доступен.<br>";
} else {
    echo "❌ YouGile не отвечает. Возможно, блокировка или проблемы с DNS.<br>";
    $error = curl_error($ch);
    if ($error) echo "Ошибка cURL: $error<br>";
}

curl_close($ch);

// Теперь пробуем авторизацию через API
echo "<h3>🔄 Пробуем API авторизацию...</h3>";

$ch = curl_init('https://yougile.com/api-v2/auth/companies');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'login' => $login,
    'password' => $password
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "<p>📡 Код ответа API: $httpCode</p>";
echo "<p>📦 Сырой ответ:</p>";
echo "<pre>";
var_dump($response);
echo "</pre>";

if ($httpCode == 200) {
    $data = json_decode($response, true);
    echo "<p>✅ Авторизация успешна!</p>";
    echo "<pre>";
    print_r($data);
    echo "</pre>";
} else {
    echo "<p style='color:red'>❌ Авторизация не удалась.</p>";
    if (empty($response)) {
        echo "<p>⚠️ Пустой ответ — сервер не отвечает или сбрасывает соединение.</p>";
    }
}

curl_close($ch);

echo "<h3>🏁 Тест завершён.</h3>";
?>