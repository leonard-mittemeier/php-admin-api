<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Восстановление пароля</title>
    <style>
        body { font-family: Arial; padding: 40px; background: #f4f4f4; }
        .container { max-width: 400px; margin: auto; background: white; padding: 30px; border-radius: 10px; }
        input, button { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; }
        button { background: #007bff; color: white; border: none; }
    </style>
</head>
<body>
<div class="container">
    <h3>Восстановление пароля</h3>
    <p>Введите email, на который зарегистрирован аккаунт.</p>
    <form method="POST" action="send_reset.php">
        <input type="email" name="email" placeholder="Ваш email" required>
        <button type="submit">Отправить код</button>
    </form>
</div>
</body>
</html>