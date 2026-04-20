<?php
session_start();
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../core/Csrf.php';

Middleware::admin();
$pdo = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (Csrf::validate($_POST['csrf_token'] ?? '')) {
        $id = $_POST['user_id'];
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: dashboard.php");
        exit;
    } else {
        die("CSRF token invalid");
    }
}

$stmt = $pdo->query("SELECT id, username, email, role FROM users");
$users = $stmt->fetchAll();

ob_start();
?>
<h2>Delete User</h2>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= Csrf::generate() ?>">
    <select name="user_id">
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?> (<?= htmlspecialchars($u['role']) ?>)</option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Delete</button>
</form>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../core/View.php';
View::render($content, "Delete User");