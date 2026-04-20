<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/core/JWTHandler.php';

$db = (new Database())->getConnection();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Email and password required']);
    exit;
}

$stmt = $db->prepare("SELECT id, username, email, password, role FROM users WHERE email = ?");
$stmt->execute([$data['email']]);
$user = $stmt->fetch();

if (!$user || !password_verify($data['password'], $user['password'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
    exit;
}

$tokens = JWTHandler::generateTokens($user['id'], $user['email'], $user['role']);

$stmt = $db->prepare("UPDATE users SET refresh_token = ? WHERE id = ?");
$stmt->execute([$tokens['refresh_token'], $user['id']]);

echo json_encode([
    'success' => true,
    'data' => [
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role']
        ],
        'tokens' => $tokens
    ]
]);