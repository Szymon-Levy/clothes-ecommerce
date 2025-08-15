<?php

include '../src/bootstrap.php';

// Convert uppercase letters in url to lower
if(preg_match('/[A-Z]/', $_SERVER['REQUEST_URI'])) {
  header("Location: //" . $_SERVER['HTTP_HOST'] . strtolower($_SERVER['REQUEST_URI']));
}

$router = new App\Router($_SERVER['REQUEST_URI'], $globals_container);
$router->route();