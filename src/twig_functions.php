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

$honeypot = new \Twig\TwigFunction('honeypot', function () {
  echo '
    <div style="opacity: 0; position: absolute; top: 0; left: 0; height: 0; width: 0; z-index: -1;">
        <label>
            leave this field blank to prove your humanity
            <input type="text" name="website" value="" autocomplete="off" tabindex="-1" />
        </label>
    </div>
  ';
});
$twig->addFunction($honeypot);

// deafult date format
$twig->getExtension(\Twig\Extension\CoreExtension::class)->setDateFormat('d/m/Y H:i', '%d days');