<?php

namespace Controllers;

use Core\GlobalsContainer;
use Core\Routing\Router;
use Twig\Environment;

abstract class BaseController
{
  protected Router $router;
  protected GlobalsContainer $globals_container;
  protected Environment $twig;

  public function __construct(GlobalsContainer $globals_container)
  {
    $this->globals_container = $globals_container;
    $this->router = $globals_container->get('router');
    $this->twig = $globals_container->get('twig');
  }
}