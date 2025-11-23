<?php

// if local environment
$isLocal = in_array($_SERVER['SERVER_NAME'], [
    'localhost',
    '127.0.0.1'
]);

// upload files directory
$publicDirName = basename(dirname($_SERVER['SCRIPT_FILENAME']));
$uploadsDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . $publicDirName . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;