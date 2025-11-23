<?php

// if local environment
$isLocal = in_array($_SERVER['SERVER_NAME'], [
    'localhost',
    '127.0.0.1'
]);

// upload files directory
$publicDir = dirname($_SERVER['SCRIPT_FILENAME']);
$uploadsDir = $publicDir . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;