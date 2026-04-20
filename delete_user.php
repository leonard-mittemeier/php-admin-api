<?php
require_once __DIR__ . '/core/Database.php';

$pdo = Database::connect();

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
$stmt->execute([$id]);

header("Location: api/users.php");
exit;