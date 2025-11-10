<?php

include '../src/bootstrap.php';

// Convert uppercase letters in url to lower
if (preg_match('/[A-Z]/', $_SERVER['REQUEST_URI'])) {
    header("Location: //" . $_SERVER['HTTP_HOST'] . strtolower($_SERVER['REQUEST_URI']));
}

// router
$router = $container->get(\Core\Routing\Router::class);

$routes = require_once $appRoot . '/src/routes.php';
$routes($router);
$router->dispatch();