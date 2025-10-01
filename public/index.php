<?php

include '../src/bootstrap.php';

// Convert uppercase letters in url to lower
if(preg_match('/[A-Z]/', $_SERVER['REQUEST_URI'])) {
  header("Location: //" . $_SERVER['HTTP_HOST'] . strtolower($_SERVER['REQUEST_URI']));
}

// router
$router = new Core\Routing\Router($globals_container);
$globals_container->set('router', $router);
$routes = require_once $app_root . '/src/routes.php';
$routes($router);
$router->dispatch();