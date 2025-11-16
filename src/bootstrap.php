<?php

$appRoot = dirname(__FILE__, 2);

require $appRoot . '/src/error_handlers.php';
require $appRoot . '/config/main.config.php';
require $appRoot . '/vendor/autoload.php';

// Errors
if ($dev === false) {
    set_exception_handler('exceptions_handling');
    set_error_handler('error_handling');
    register_shutdown_function('shutdown_handling');
}

// Container
$container = new Core\Container\Container();

// Database
$container->set(Core\Database\DataBase::class, function($c) {
    $config = $c->get(Core\Config\Config::class)->database();
    
    extract($config);

    $dsn = "{$type}:host={$host};dbname={$dbName};port={$port};charset={$characterEncoding}";
    
    return new Core\Database\DataBase($dsn, $user, $password);
});

// Csrf
$container->get(Core\Http\Csrf::class)->setInCookie();