<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../app/core/JWTHandler.php';

\ = json_decode(file_get_contents('php://input'), true);

if (!isset(\['refresh_token'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Refresh token required']);
    exit;
}

\ = JWTHandler::refreshAccessToken(\['refresh_token']);

if (!\) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Invalid refresh token']);
    exit;
}

echo json_encode(['success' => true, 'data' => \]);
