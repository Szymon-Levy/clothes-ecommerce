<?php

namespace Controllers;

use Core\GlobalsContainer;
use Core\Routing\Router;
use Twig\Environment;
use Core\Models;
use Core\Session;
use Core\Utils;

abstract class BaseController
{
  protected Router $router;
  protected Environment $twig;
  protected Models $models;
  protected Session $session;
  protected array $email_settings;
  protected Utils $utils;
  protected array $global_vars;

  public function __construct(GlobalsContainer $globals_container)
  {
    $this->router = $globals_container->get('router');
    $this->twig = $globals_container->get('twig');
    $this->models = $globals_container->get('models');
    $this->session = $globals_container->get('session');
    $this->email_settings = $globals_container->get('email_settings');
    $this->utils = $globals_container->get('utils');
    $this->global_vars = $globals_container->get('global_vars');
  }

  protected function renderView(string $path, array $data = [])
  {
    echo $this->twig->render($path, $data);
  }
}