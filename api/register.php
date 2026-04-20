<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';

$db = (new Database())->getConnection();

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Все поля обязательны']);
    exit;
}

$checkStmt = $db->prepare("SELECT id FROM users WHERE email = ?");
$checkStmt->execute([$email]);
if ($checkStmt->fetch()) {
    http_response_code(409);
    echo json_encode(['success' => false, 'error' => 'Пользователь с таким email уже существует']);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->execute([$username, $email, $hash]);
$userId = $db->lastInsertId();

// YouGile интеграция
$yougileApiKey = 'NRi81gZm4BXUaPcQfBfmZ0gummc0eNwRmEQt4dNfrOvJrto6vyGpGT18DXo4tKxw';

$ch = curl_init('https://yougile.com/api-v2/tasks');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $yougileApiKey
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'title' => 'Клиент: ' . $username,
    'description' => $email,
    'assignedTo' => '2c1f16de-0e88-4501-9e76-0909d921f8a0'
]));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_exec($ch);

echo json_encode([
    'success' => true,
    'user_id' => $userId
]);