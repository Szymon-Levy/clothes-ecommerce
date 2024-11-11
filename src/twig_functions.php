<?php

$show_file_modification_time = new \Twig\TwigFunction('show_file_modification_time', function (string $path) {
  $new_path = $path;
  if(file_exists($path)) {
    $new_path = $new_path . '?v=' . filemtime($path);
  }
  return $new_path;
});
$twig->addFunction($show_file_modification_time);

$page_active_status = new \Twig\TwigFunction('page_active_status', function (string $current_page, string|null $url_part) {
  if($current_page == $url_part) {
    return 'active';
  }
  return '';
});
$twig->addFunction($page_active_status);