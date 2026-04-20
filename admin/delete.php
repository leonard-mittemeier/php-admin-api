<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['user_id'])) {
    header("Location: users.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
$db = (new Database())->getConnection();

$stmt = $db->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$_POST['user_id']]);

header("Location: users.php?message=user_deleted");
exit;