<?php

use Core\Routing\Router;

return function(Router $router) {
  $router->add(
    'GET', '/', fn() => 'index'
  );
};