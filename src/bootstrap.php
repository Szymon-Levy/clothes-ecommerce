<?php

$appRoot = dirname(__FILE__, 2);

require_once $appRoot . '/src/error_handlers.php';
require_once $appRoot . '/config/main.config.php';
require_once $appRoot . '/vendor/autoload.php';

// Errors
if ($dev === false) {
    set_exception_handler('exceptions_handling');
    set_error_handler('error_handling');
    register_shutdown_function('shutdown_handling');
}