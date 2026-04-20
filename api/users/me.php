<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../app/core/AuthMiddleware.php';

$user = AuthMiddleware::authenticate();
AuthMiddleware::requireRole(['admin', 'user']);

echo json_encode(['success' => true, 'data' => ['user' => $user]]);