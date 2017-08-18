<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . env('DB_HOST', '127.0.0.1') . ';dbname=' . env('DB_NAME', 'yii2basic'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', 'root'),
    'charset' => env('DB_CHARSET', 'utf8'),
];
