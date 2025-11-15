<?php

$type = 'mysql';
$host = 'localhost';
$dbName = '';
$port = '3306';
$characterEncoding = 'utf8mb4';

return ['database_dev', [
        'user' => 'root',
        'password' => '',
        'dsn' => "$type:host=$host;dbname=$dbName;port=$port;charset=$characterEncoding",
    ]
];