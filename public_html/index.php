<?php

include '../src/bootstrap.php';

$router = new ClothesEcommerce\App\Router($_SERVER['REQUEST_URI'], $twig);
$url_parts = $router->getUrlParts();

include $router->route();