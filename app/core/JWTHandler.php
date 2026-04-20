<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler
{
    private static $secretKey = 'your-secret-key-change-this-2026';
    private static $algorithm = 'HS256';
    private static $accessTokenExpire = 3600;
    private static $refreshTokenExpire = 604800;

    public static function generateTokens($userId, $email, $role)
    {
        $accessPayload = [
            'user_id' => $userId,
            'email' => $email,
            'role' => $role,
            'type' => 'access',
            'iat' => time(),
            'exp' => time() + self::$accessTokenExpire
        ];

        $refreshPayload = [
            'user_id' => $userId,
            'type' => 'refresh',
            'iat' => time(),
            'exp' => time() + self::$refreshTokenExpire
        ];

        return [
            'access_token' => JWT::encode($accessPayload, self::$secretKey, self::$algorithm),
            'refresh_token' => JWT::encode($refreshPayload, self::$secretKey, self::$algorithm),
            'expires_in' => self::$accessTokenExpire
        ];
    }

    public static function verifyToken($token, $expectedType = 'access')
    {
        try {
            $decoded = JWT::decode($token, new Key(self::$secretKey, self::$algorithm));
            
            if ($decoded->type !== $expectedType) {
                return false;
            }

            return (array) $decoded;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function refreshAccessToken($refreshToken)
    {
        $decoded = self::verifyToken($refreshToken, 'refresh');
        
        if (!$decoded) {
            return false;
        }

        return self::generateTokens(
            $decoded['user_id'],
            $decoded['email'] ?? '',
            $decoded['role'] ?? 'user'
        );
    }
}