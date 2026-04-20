<?php
return [
    // Используй тот сервис, который удобнее
    'yandex' => [
        'host' => 'smtp.yandex.ru',
        'username' => 'твой_логин@yandex.ru',
        'password' => 'твой_пароль',
        'from' => 'твой_логин@yandex.ru',
        'from_name' => 'Hao Shop'
    ],
    
    'gmail' => [
        'host' => 'smtp.gmail.com',
        'username' => 'твой@gmail.com',
        'password' => 'пароль_приложения', // не обычный, а сгенерированный!
        'from' => 'твой@gmail.com',
        'from_name' => 'Hao Shop'
    ],
    
    'mailru' => [
        'host' => 'smtp.mail.ru',
        'username' => 'твой@mail.ru',
        'password' => 'твой_пароль',
        'from' => 'твой@mail.ru',
        'from_name' => 'Hao Shop'
    ]
];
