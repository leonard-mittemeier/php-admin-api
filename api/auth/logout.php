<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/core/AuthMiddleware.php';

\ = AuthMiddleware::authenticate();

\ = json_decode(file_get_contents('php://input'), true);

if (isset(\['refresh_token'])) {
    \ = (new Database())->getConnection();
    \ = \->prepare("UPDATE users SET refresh_token = NULL WHERE refresh_token = ?");
    \->execute([\['refresh_token']]);
}

echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
