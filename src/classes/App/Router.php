<?php

namespace ClothesEcommerce\App;

class Router 
{
  private string $url;
  private \Twig\Environment $twig;
  private GlobalsContainer $globals_container;
  private null|array $url_parts = null;
  private string $path = '';
  private bool $is_admin = false;

  public function __construct(string $url, GlobalsContainer $globals_container)
  {
    $this->url = mb_strtolower($url);
    $this->globals_container = $globals_container;
    $this->twig = $this->globals_container->get('twig');
    $this->getUrlParts();
    $this->getUrlStringPath();
    $this->checkIfAdminController();
  }

  public function route():void
  {
    // Get global variables to pass to pages scope
    $app = $this->globals_container->get('app');
    $twig = $this->twig;
    $session = $this->globals_container->get('session');
    $email_settings = $this->globals_container->get('email_settings');
    $url_parts = $this->url_parts;
    
    $controller_dir = $this->getControllersDir();
    $controller_path = '';
    $this->getUrlStringPath(true);
    
    if (!empty($this->path)) {
      $controller_path = $controller_dir . $this->path . '/index.php';
      if (!file_exists($controller_path) && end($this->url_parts) != 'index') {
        $controller_path = $controller_dir . $this->path . '.php';
      }
    }
    else {
      $controller_path = $controller_dir . 'index.php';
    }

    // error_log(print_r($controller_path, TRUE));

    if (!file_exists($controller_path)) {
      http_response_code(404);
      $controller_path = APP_ROOT . '/src/controllers/front/404.php';
    }

    // if (!empty($this->url_parts) && $this->url_parts[0] === 'admin') {
    //   if ($this->checkIfNotAdmin() && (!isset($this->url_parts[1]) || $this->url_parts[1] !== 'login')) {
    //       header('Location: /admin/login');
    //       exit;
    //   }
    // }

    require_once $controller_path;
  }

  private function getUrlParts():void 
  {
    $this->getUrlStringPath(false);
    $this->url_parts = explode('/', trim($this->path, '/'));
    $this->passUrlPartsToTwig();
  }

  private function getUrlStringPath(bool $cutAdminPart = false):void
  {
    $path = parse_url($this->url, PHP_URL_PATH);
    $cutPart = '';
    $cutPart = $cutAdminPart && $this->is_admin ? 'admin/' : '';
    $this->path = substr($path, strlen(DOC_ROOT . $cutPart));
  }

  private function passUrlPartsToTwig():void
  {
    $this->twig->addGlobal('url_parts', $this->url_parts);
  }

  private function getControllersDir(): string
  {
    $path = APP_ROOT . '/src/controllers/';
    $source = $this->is_admin ? 'admin' : 'front';
    return $path . $source . '/';
  }

  private function checkIfAdminController():void
  {
    $this->is_admin = !empty($this->url_parts) && $this->url_parts[0] === 'admin';
  }

  // private function checkIfNotAdmin(): bool
  // {
  //   $session = $this->globals_container->get('session');
  //   return !isset($session['user']) || $session['user']['role'] !== 'admin';
  // }
}