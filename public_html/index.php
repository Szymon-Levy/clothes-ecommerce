<?php

include '../src/bootstrap.php';

$router = new ClothesEcommerce\App\Router($_SERVER['REQUEST_URI'], $globals_container);
$url_parts = $router->getUrlParts();

$router->route();