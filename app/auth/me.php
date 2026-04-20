<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../app/core/AuthMiddleware.php';

// Этот маршрут требует авторизации
$user = AuthMiddleware::authenticate();

// Можно проверить роль
AuthMiddleware::requireRole(['admin', 'user']);

echo json_encode([
    'success' => true,
    'data' => [
        'user' => $user
    ]
]);