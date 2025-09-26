<?php

namespace Controllers;

use Core\GlobalsContainer;
use Core\Routing\Router;
use Twig\Environment;
use Core\Models;
use Core\Session;
use Core\Helpers;

abstract class BaseController
{
  protected GlobalsContainer $globals_container;
  protected Router $router;
  protected Environment $twig;
  protected Models $models;
  protected Session $session;
  protected array $email_settings;
  protected Helpers $helpers;

  public function __construct(GlobalsContainer $globals_container)
  {
    $this->globals_container = $globals_container;
    $this->router = $globals_container->get('router');
    $this->twig = $globals_container->get('twig');
    $this->models = $globals_container->get('models');
    $this->session = $globals_container->get('session');
    $this->email_settings = $globals_container->get('email_settings');
    $this->helpers = $globals_container->get('helpers');
  }

  protected function renderView(string $path, array $data = [])
  {
    echo $this->twig->render($path, $data);
  }
}