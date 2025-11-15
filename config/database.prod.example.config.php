<?php

$type = 'mysql';
$host = '';
$dbName = '';
$port = '3306';
$characterEncoding = 'utf8mb4';

return ['database_prod', [
        'user' => '',
        'password' => '',
        'dsn' => "$type:host=$host;dbname=$dbName;port=$port;charset=$characterEncoding",
    ]
];