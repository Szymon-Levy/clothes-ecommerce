<?php

$show_file_modification_time = new \Twig\TwigFunction('show_file_modification_time', function (string $path) {
  $new_path = $path;
  if(file_exists($path)) {
    $new_path = $new_path . '?v=' . filemtime($path);
  }
  return $new_path;
});
$twig->addFunction($show_file_modification_time);