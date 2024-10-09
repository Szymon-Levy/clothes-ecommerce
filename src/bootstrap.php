<?php

define('APP_ROOT', dirname(__FILE__, 2));

require APP_ROOT . '/src/functions.php';
require APP_ROOT . '/config/config.php';
require APP_ROOT . '/vendor/autoload.php';

// App object
$app = new \ClothesEcommerce\App\App($dsn, $db_user, $db_password);
unset ($dsn, $db_user, $db_password);

// Twig loading
$twig_settings['cache'] = APP_ROOT . '/var/cache';
$twig_settings['debug'] = DEV;
$twig_loader = new Twig\Loader\FilesystemLoader(APP_ROOT . '/templates');
$twig = new Twig\Environment($twig_loader, $twig_settings);
$twig->addGlobal('doc_root', DOC_ROOT);
if (DEV === true) {
  $twig->addExtension(new \Twig\Extension\DebugExtension());
}