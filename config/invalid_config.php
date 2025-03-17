<?php
return [
    'database' => [
        'driver' => 'mysql',
        'host' => 'invalid_host',
        'port' => '3306',
        'dbname' => 'wrong_db',
        'username' => 'wrong_user',
        'password' => 'wrong_pass',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ]
];
