<?php

include '../src/bootstrap.php';

// simple routing
$path = mb_strtolower($_SERVER['REQUEST_URI']);
$path = substr($path, strlen(DOC_ROOT));
$url_parts = explode('/', $path);

if (!empty($url_parts[0]) && is_dir(APP_ROOT . '/src/pages/' . $url_parts[0])) {
  $page = !empty($url_parts[1]) ? $url_parts[1] : 'index';
  $page_php = APP_ROOT . '/src/pages/' . $url_parts[0] . '/' . $page . '.php';
}
else {
  $page = !empty($url_parts[0]) ? $url_parts[0] : 'index';
  $page_php = APP_ROOT . '/src/pages/' . $page . '.php';
}

if (!file_exists($page_php)) {
  $page_php = APP_ROOT . '/src/pages/404.php';
  $twig->addGlobal('page_404', true);
}
include $page_php;