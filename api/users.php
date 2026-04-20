<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$db = (new Database())->getConnection();

switch ($method) {
    case 'GET':
        $stmt = $db->query("SELECT id, username, email, created_at FROM users");
        $users = $stmt->fetchAll();
        echo json_encode(["success" => true, "data" => $users]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !isset($data['username'], $data['email'], $data['password'])) {
            http_response_code(400);
            echo json_encode(["error" => "Недостаточно данных"]);
            break;
        }

        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$data['username'], $data['email'], $hash]);

        echo json_encode(["success" => true, "id" => $db->lastInsertId()]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Метод не поддерживается"]);
}