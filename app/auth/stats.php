<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../app/core/AuthMiddleware.php';
require_once __DIR__ . '/../../config/database.php';

// Только админы
AuthMiddleware::authenticate();
AuthMiddleware::requireRole('admin');

$db = (new Database())->getConnection();

$stats = [
    'total_users' => $db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'total_orders' => 0, // пример
    'last_24h_users' => $db->query("SELECT COUNT(*) FROM users WHERE created_at > NOW() - INTERVAL 1 DAY")->fetchColumn()
];

echo json_encode([
    'success' => true,
    'data' => $stats
]);
