<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/core/AuthMiddleware.php';

// Проверяем токен (необязательно, но можно)
$user = AuthMiddleware::authenticate();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['refresh_token'])) {
    $db = (new Database())->getConnection();
    
    // Удаляем refresh token из БД
    $stmt = $db->prepare("UPDATE users SET refresh_token = NULL WHERE refresh_token = ?");
    $stmt->execute([$data['refresh_token']]);
}

echo json_encode([
    'success' => true,
    'message' => 'Logged out successfully'
]);