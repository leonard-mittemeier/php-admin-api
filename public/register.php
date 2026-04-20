<?php
$recaptchaConfig = require __DIR__ . '/../config/recaptcha.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Регистрация с reCAPTCHA</title>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= $recaptchaConfig['site_key'] ?>"></script>
    <script>
        grecaptcha.ready(function() {
            document.getElementById('register-form').addEventListener('submit', function(e) {
                e.preventDefault();
                grecaptcha.execute('<?= $recaptchaConfig['site_key'] ?>', {action: 'register'}).then(function(token) {
                    let form = document.getElementById('register-form');
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'recaptcha_token';
                    input.value = token;
                    form.appendChild(input);
                    form.submit();
                });
            });
        });
    </script>
</head>
<body>
    <form id="register-form" method="POST" action="api/register.php">
        <input type="text" name="username" placeholder="Имя" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Зарегистрироваться</button>
    </form>
</body>
</html>