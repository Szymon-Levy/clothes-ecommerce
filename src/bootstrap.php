<?php

define('APP_ROOT', dirname(__FILE__, 2));

require APP_ROOT . '/src/helpers.php';
require APP_ROOT . '/src/error_handlers.php';
require APP_ROOT . '/config/config.php';
require APP_ROOT . '/vendor/autoload.php';

// Errors
if (DEV === false) {
  set_exception_handler('exceptions_handling');
  set_error_handler('error_handling');
  register_shutdown_function('shutdown_handling');
}

// Twig loading
$twig_settings['cache'] = APP_ROOT . '/var/cache';
$twig_settings['debug'] = DEV;
$twig_loader = new Twig\Loader\FilesystemLoader(APP_ROOT . '/src/views');
$twig = new Twig\Environment($twig_loader, $twig_settings);
$twig->addGlobal('doc_root', DOC_ROOT);
$twig->addGlobal('admin_pagination', ADMIN_PAGINATION);

// Twig access to session
$session = new Core\Session();
$twig->addGlobal('session', $session);

// Add footer info to twig
$footer_info = [
  'shop_name' => SHOP_NAME,
  'shop_email' => SHOP_EMAIL,
  'shop_address' => SHOP_ADDRESS,
  'shop_phone' => SHOP_PHONE,
];
$twig->addGlobal('footer_info', $footer_info);

if (DEV === true) {
  $twig->addExtension(new Twig\Extension\DebugExtension());
}

// Add Twig custom functions and modifications
require APP_ROOT . '/src/twig_extensions.php';

// Create utils instance
$utils = new Core\Utils($session);

// Create container for variables
$globals_container = new Core\GlobalsContainer();
$globals_container->set('twig', $twig);
$globals_container->set('session', $session);
$globals_container->set('email_settings', $email_settings);
$globals_container->set('utils', $utils);

// Models container
$models = new Core\Models($dsn, $db_user, $db_password, $globals_container);
unset ($dsn, $db_user, $db_password);
$globals_container->set('models', $models);