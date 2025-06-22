<?php

/**
 * Returns static asset's link with file modification time as a parameter of url
 * @param string $file_path - path to static asset from public folder
 * @return string - full asset url with cache busting method
 */
$assets = new \Twig\TwigFunction('assets', function (string $file_path) {
  if(file_exists($file_path)) {
    $file_path .= '?v=' . filemtime($file_path);
  }
  return DOC_ROOT . $file_path;
});
$twig->addFunction($assets);

/**
 * Creates linking to scripts loaded on specific pages taken from js page folder
 * @param array|string $file_names - names of files as array or one file as string
 * @param string $source - front/admin source of files
 * @return string - script tags with linked files
 */
$loadPageJs = new \Twig\TwigFunction('loadPageJs', function (array|string $file_names, string $source) {
  if (is_string($file_names)) {
    $file_names = explode(' ', $file_names);
  }
  foreach($file_names as $file_name) {
    $file_path = 'js/' . $source . '/pages/' . $file_name . '.js';

    if(file_exists($file_path)) {
      $file_path .= '?v=' . filemtime($file_path);
      $full_path = DOC_ROOT . $file_path;
      echo '<script src="' . $full_path . '" defer type="module"></script>';
    }
  }
});
$twig->addFunction($loadPageJs);

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