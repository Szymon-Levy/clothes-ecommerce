<?php

// if local environment
$isLocal = in_array($_SERVER['SERVER_NAME'], [
    'localhost',
    '127.0.0.1'
]);

// dynamic document root working in subdirectories
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

$basePath = dirname($_SERVER['SCRIPT_NAME']);
$basePath = str_replace('\\', '/', $basePath);

$docRoot = $protocol . '://' . $host . $basePath;

// upload files directory
$publicDirName = basename(dirname($_SERVER['SCRIPT_FILENAME']));
$uploadsDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . $publicDirName . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;