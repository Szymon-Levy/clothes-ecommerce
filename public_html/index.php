<?php

include '../src/bootstrap.php';

// simple routing
$path = mb_strtolower(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$path = substr($path, strlen(DOC_ROOT));
$url_parts = explode('/', $path);
$twig->addGlobal('url_parts', $url_parts);
// echo '<pre>';
// var_dump($url_parts);
// var_dump($path);

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
  $page_php = $pages_dir . '404.php';
}
include $page_php;