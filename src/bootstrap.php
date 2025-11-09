<?php

$app_root = dirname(__FILE__, 2);

require $app_root . '/src/error_handlers.php';
require $app_root . '/config/main.config.php';
require $app_root . '/vendor/autoload.php';

// Errors
if ($dev === false) {
    set_exception_handler('exceptions_handling');
    set_error_handler('error_handling');
    register_shutdown_function('shutdown_handling');
}

// Container
$container = new Core\Container();

// Database
$container->set(Core\DataBase::class, function($c) {
    $dsn = $c->get(Core\Config::class)->database('dsn');
    $user = $c->get(Core\Config::class)->database('user');
    $password = $c->get(Core\Config::class)->database('password');
    
    return new Core\DataBase($dsn, $user, $password);
});

// Csrf
$container->get(Core\Csrf::class)->setInCookie();