<?php
return [
    'database' => [
        'driver' => 'pgsql',
        'host' => 'localhost',
        'port' => 6432,
        'dbname' => 'test_football_league',
        'username' => 'invoice',
        'password' => 'q13kTkO58',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    ]
];