<?php

namespace ClothesEcommerce\App;

class Router 
{
  private string $url;
  private \Twig\Environment $twig;

  public function __construct(string $url, \Twig\Environment $twig)
  {
    $this->url = $url;
    $this->twig = $twig;
  }

  public function route():string
  {
    $url_parts = $this->getUrlParts();
    $this->addUrlPartsToTwig($url_parts);
    
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
      return $page_php;
      exit;
    }
    return $page_php;
  }

  public function getUrlParts():array 
  {
    $path = $this->getUrlStringPath();
    return explode('/', trim($path, '/'));
  }

  private function getUrlStringPath():string
  {
    $path = mb_strtolower(parse_url($this->url, PHP_URL_PATH));
    $path = substr($path, strlen(DOC_ROOT));
    return $path;
  }

  private function addUrlPartsToTwig(array $url_parts):void
  {
    $this->twig->addGlobal('url_parts', $url_parts);
  }
}