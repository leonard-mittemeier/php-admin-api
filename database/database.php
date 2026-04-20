<?php
return [
    'db_host' => 'localhost',
    'db_name' => 'php_admin',
    'db_user' => 'Hao Asakura',
    'db_pass' => 'DUgr@diGdoShabranigdo',
    'charset' => 'utf8mb4',

    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
];