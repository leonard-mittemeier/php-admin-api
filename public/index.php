<?php
session_start();

// Если пользователь уже вошёл — кидаем в личный кабинет
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hao Shop | Вход / Регистрация</title>
    <style>
        body { font-family: Arial; padding: 40px; background: #f4f4f4; }
        .container { max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 10px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px; }
        .tabs { display: flex; margin-bottom: 20px; }
        .tab { flex: 1; text-align: center; padding: 10px; cursor: pointer; background: #eee; }
        .tab.active { background: #007bff; color: white; }
        .form { display: none; }
        .form.active { display: block; }
    </style>
</head>
<body>
<div class="container">
    <h2>Добро пожаловать в Hao Shop!</h2>

    <div class="tabs">
        <div class="tab active" onclick="showForm('login')">Вход</div>
        <div class="tab" onclick="showForm('register')">Регистрация</div>
    </div>

    <!-- Форма входа -->
    <div id="loginForm" class="form active">
        <form method="POST" action="login.php">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
    </div>

    <!-- Форма регистрации -->
    <div id="registerForm" class="form">
        <form method="POST" action="register.php">
            <input type="text" name="username" placeholder="Имя пользователя" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="password" name="confirm" placeholder="Подтвердите пароль" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div style="margin-top: 20px; padding: 10px; background: #e7f3e7;">
            <?= htmlspecialchars($_GET['message']) ?>
        </div>
    <?php endif; ?>
</div>

<script>
function showForm(type) {
    document.querySelectorAll('.form').forEach(f => f.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.getElementById(type + 'Form').classList.add('active');
    event.target.classList.add('active');
}
</script>
</body>
</html>