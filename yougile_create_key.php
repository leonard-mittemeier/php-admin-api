<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "=== СОЗДАНИЕ API-КЛЮЧА YOUGILE ===\n\n";

$login = 'likoishepard@gmail.com';
$password = 'NormandySR2';
$companyId = '2c1f16de-0e88-4501-9e76-0909d921f8a0';

$ch = curl_init('https://yougile.com/api-v2/auth/keys');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'login' => $login,
    'password' => $password,
    'companyId' => $companyId
]));
curl_setopt($ch, CURLOPT_SSL_OPTIONS, CURLSSLOPT_NATIVE_CA);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP код: " . $httpCode . "\n";
echo "Ответ:\n" . $response . "\n";
?>