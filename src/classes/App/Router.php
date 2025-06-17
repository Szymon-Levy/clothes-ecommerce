<?php

namespace ClothesEcommerce\App;

class Router 
{
  private string $url;
  private \Twig\Environment $twig;
  private GlobalsContainer $globals_container;

  public function __construct(string $url, GlobalsContainer $globals_container)
  {
    $this->url = mb_strtolower($url);
    $this->globals_container = $globals_container;
    $this->twig = $this->globals_container->get('twig');
  }

  public function route():void
  {
    // Get global variables to pass to pages scope
    $app = $this->globals_container->get('app');
    $twig = $this->twig;
    $session = $this->globals_container->get('session');
    $email_settings = $this->globals_container->get('email_settings');

    $url_parts = $this->getUrlParts();
    $this->passUrlPartsToTwig($url_parts);
    
    $pages_dir = APP_ROOT . '/src/pages/';
    $path = $this->getUrlStringPath();
    $page_php = null;

    if (!empty($path)) {
      if (file_exists($pages_dir . $path . '/index.php')) {
        $page_php = $pages_dir . $path . '/index.php';
      }
      else {
        if ($url_parts[count($url_parts) - 1] != 'index') {
          $page_php = $pages_dir . $path . '.php';
        }
      }
    }
    else {
      $page_php = $pages_dir . 'index.php';
    }

    if (!file_exists($page_php)) {
      http_response_code(404);
      $page_php = $pages_dir . '404.php';
    }

    // if (!empty($url_parts) && $url_parts[0] === 'admin') {
    //   if ($this->checkIfNotAdmin() && (!isset($url_parts[1]) || $url_parts[1] !== 'login')) {
    //       header('Location: /admin/login');
    //       exit;
    //   }
    // }

    require_once $page_php;
  }

  public function getUrlParts():array 
  {
    $path = $this->getUrlStringPath();
    return explode('/', trim($path, '/'));
  }

  private function getUrlStringPath():string
  {
    $path = parse_url($this->url, PHP_URL_PATH);
    $path = substr($path, strlen(DOC_ROOT));
    return $path;
  }

  private function passUrlPartsToTwig(array $url_parts):void
  {
    $this->twig->addGlobal('url_parts', $url_parts);
  }

  // private function checkIfNotAdmin(): bool
  // {
  //   $session = $this->globals_container->get('session');
  //   return !isset($session['user']) || $session['user']['role'] !== 'admin';
  // }
}