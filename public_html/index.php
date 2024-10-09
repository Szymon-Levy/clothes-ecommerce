<?php

include '../src/bootstrap.php';

// simple routing
$path = mb_strtolower($_SERVER['REQUEST_URI']);
$path = substr($path, strlen(DOC_ROOT));
$url_parts = explode('/', $path);

$page = $url_parts[0] ?: 'home';
$page_php = APP_ROOT . '/src/pages/' . $page . '.php';
if (!file_exists($page_php)) {
  $page_php = APP_ROOT . '/src/pages/404.php';
}
include $page_php;