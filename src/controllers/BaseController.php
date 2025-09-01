<?php

namespace Controllers;

use Core\GlobalsContainer;
use Core\Routing\Router;
use Twig\Environment;
use Core\App;
use Core\Session;

abstract class BaseController
{
  protected GlobalsContainer $globals_container;
  protected Router $router;
  protected Environment $twig;
  protected App $app;
  protected Session $session;
  protected array $email_settings;

  public function __construct(GlobalsContainer $globals_container)
  {
    $this->globals_container = $globals_container;
    $this->router = $globals_container->get('router');
    $this->twig = $globals_container->get('twig');
    $this->app = $globals_container->get('app');
    $this->session = $globals_container->get('session');
    $this->email_settings = $globals_container->get('email_settings');
  }
}