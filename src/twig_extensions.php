<?php

/**
 * Returns static asset's link with file modification time as a parameter of url
 * @param string $file_path - path to static asset from public folder
 * @return string - full asset url with cache busting param
 */
$assets = new Twig\TwigFunction('assets', function (string $file_path) use ($doc_root) {
  if (file_exists($file_path)) {
    $file_path .= '?v=' . filemtime($file_path);
  }

  return $doc_root . $file_path;
});

$twig->addFunction($assets);

/**
 * Creates linking to scripts loaded on specific pages taken from js page folder
 * @param array|string $file_names - names of files as array or one file as string
 * @param string $source - front/admin source of files
 * @return string - script tags with linked files
 */
$loadPageJs = new Twig\TwigFunction('loadPageJs', function (array|string $file_names, string $source) use ($doc_root) {
  if (is_string($file_names)) {
    $file_name = $file_names;
    $file_names = [];
    $file_names[] = $file_name;
  }

  foreach ($file_names as $file_name) {
    $file_path = 'js/' . $source . '/pages/' . $file_name . '.js';

    if (file_exists($file_path)) {
      $file_path .= '?v=' . filemtime($file_path);
      $full_path = $doc_root . $file_path;
      echo '<script src="' . $full_path . '" defer type="module"></script>';
    }
  }
});

$twig->addFunction($loadPageJs);

/**
 * Compares given string with given part of url and returns string 'active' or empty
 * @param string $current_page - part of url to check with part of actual url parts array from router
 * @param string|null $url_part - part of url taken from array from router to compare
 * @return string - class name that will be printed in element to highlight in html
 */
$pageActiveStatus = new Twig\TwigFunction('pageActiveStatus', function (string $current_page, string|null $url_part) {
  if ($current_page == $url_part) {
    return 'active';
  }

  return '';
});

$twig->addFunction($pageActiveStatus);

/**
 * Prints honeypot form element
 */
$honeypot = new Twig\TwigFunction('honeypot', function () {
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
$twig->getExtension(Twig\Extension\CoreExtension::class)->setDateFormat('d/m/Y H:i', '%d days');