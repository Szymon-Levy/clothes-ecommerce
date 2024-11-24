<?php

$showFileModificationTime = new \Twig\TwigFunction('showFileModificationTime', function (string $path) {
  $new_path = $path;
  if(file_exists($path)) {
    $new_path = $new_path . '?v=' . filemtime($path);
  }
  return $new_path;
});
$twig->addFunction($showFileModificationTime);

$pageActiveStatus = new \Twig\TwigFunction('pageActiveStatus', function (string $current_page, string|null $url_part) {
  if($current_page == $url_part) {
    return 'active';
  }
  return '';
});
$twig->addFunction($pageActiveStatus);