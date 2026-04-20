<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

class Mailer
{
    private $config;

    public function __construct($provider = 'yandex')
    {
        $this->config = require __DIR__ . '/../../config/mail.php';
        $this->provider = $this->config[$provider] ?? $this->config['yandex'];
    }

    public function sendCode($to, $code)
    {
        $mail = new PHPMailer(true);

        try {
            // Настройки сервера
            $mail->isSMTP();
            $mail->Host = $this->provider['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->provider['username'];
            $mail->Password = $this->provider['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            // Отправитель и получатель
            $mail->setFrom($this->provider['from'], $this->provider['from_name']);
            $mail->addAddress($to);

            // Контент
            $mail->isHTML(true);
            $mail->Subject = 'Код подтверждения | Hao Shop';
            $mail->Body = "
                <h2>Добро пожаловать в Hao Shop!</h2>
                <p>Ваш код подтверждения:</p>
                <h1 style='color: #007bff;'>{$code}</h1>
                <p>Введите этот код на сайте для завершения регистрации.</p>
                <hr>
                <small>Если вы не регистрировались, просто проигнорируйте это письмо.</small>
            ";
            $mail->AltBody = "Ваш код подтверждения: {$code}";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail error: " . $mail->ErrorInfo);
            return false;
        }
    }
}