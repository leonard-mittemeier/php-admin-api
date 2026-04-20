<?php
require_once __DIR__ . '/JWTHandler.php';

class AuthMiddleware
{
    /**
     * Проверяет наличие и валидность токена
     */
    public static function authenticate()
    {
        $headers = getallheaders();
        
        if (!isset($headers['Authorization'])) {
            self::unauthorized('Authorization header not found');
        }

        $authHeader = $headers['Authorization'];
        
        // Ожидаем формат "Bearer <token>"
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            self::unauthorized('Invalid authorization format');
        }

        $token = $matches[1];
        $payload = JWTHandler::verifyToken($token, 'access');

        if (!$payload) {
            self::unauthorized('Invalid or expired token');
        }

        // Сохраняем данные пользователя для использования в маршрутах
        $_REQUEST['auth_user'] = $payload;
        
        return $payload;
    }

    /**
     * Проверяет, имеет ли пользователь нужную роль
     */
    public static function requireRole($requiredRole)
    {
        $user = $_REQUEST['auth_user'] ?? null;
        
        if (!$user) {
            self::forbidden('User not authenticated');
        }

        // Преобразуем строку роли в массив для поддержки нескольких ролей
        $requiredRoles = is_array($requiredRole) ? $requiredRole : [$requiredRole];
        
        if (!in_array($user['role'], $requiredRoles)) {
            self::forbidden('Insufficient permissions');
        }

        return true;
    }

    private static function unauthorized($message)
    {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'unauthorized',
            'message' => $message
        ]);
        exit;
    }

    private static function forbidden($message)
    {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'forbidden',
            'message' => $message
        ]);
        exit;
    }
}