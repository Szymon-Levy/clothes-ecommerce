<?php

include '../src/bootstrap.php';

// $router = new ClothesEcommerce\App\Router();
include ClothesEcommerce\App\Router::route($_SERVER['REQUEST_URI'], $twig);