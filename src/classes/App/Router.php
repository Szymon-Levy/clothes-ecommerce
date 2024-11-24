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

    if (!empty($url_parts[0]) && is_dir($pages_dir . $url_parts[0])) {
      $page = !empty($url_parts[1]) ? $url_parts[1] : 'index';
      $page_php = $pages_dir . $url_parts[0] . '/' . $page . '.php';
    }
    else {
      $page = !empty($url_parts[0]) ? $url_parts[0] : 'index';
      $page_php = $pages_dir . $page . '.php';
    }

    if (!file_exists($page_php)) {
      http_response_code(404);
      $page_php = $pages_dir . '404.php';
    }
    return $page_php;
  }

  public function getUrlParts():array 
  {
    $path = mb_strtolower(parse_url($this->url, PHP_URL_PATH));
    $path = substr($path, strlen(DOC_ROOT));
    return explode('/', $path);
  }

  private function addUrlPartsToTwig(array $url_parts)
  {
    $this->twig->addGlobal('url_parts', $url_parts);
  }
}