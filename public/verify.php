<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Подтверждение email</title>
    <style>
        body { font-family: Arial; padding: 40px; background: #f4f4f4; }
        .container { max-width: 400px; margin: auto; background: white; padding: 30px; border-radius: 10px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px; }
    </style>
</head>
<body>
<div class="container">
    <h3>Подтверждение email</h3>
    <p>Код отправлен на <strong><?= htmlspecialchars($_GET['email'] ?? '') ?></strong></p>

    <form method="POST" action="confirm.php">
        <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
        <input type="text" name="code" placeholder="6-значный код" required>
        <button type="submit">Подтвердить</button>
    </form>
</div>
</body>
</html>