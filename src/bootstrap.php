<?php

$app_root = dirname(__FILE__, 2);

require $app_root . '/src/error_handlers.php';
require $app_root . '/config/config.php';
require $app_root . '/vendor/autoload.php';

// Errors
if ($dev === false) {
    set_exception_handler('exceptions_handling');
    set_error_handler('error_handling');
    register_shutdown_function('shutdown_handling');
}

// Twig loading
$twig_settings['cache'] = $app_root . '/var/cache';
$twig_settings['debug'] = $dev;
$twig_loader = new Twig\Loader\FilesystemLoader($app_root . '/src/views');
$twig = new Twig\Environment($twig_loader, $twig_settings);
$twig->addGlobal('global_vars', $global_vars);

// Twig access to session
$session = new Core\Session();
$twig->addGlobal('session', $session);

if ($dev === true) {
    $twig->addExtension(new Twig\Extension\DebugExtension());
}

// Add Twig custom functions and modifications
require $app_root . '/src/twig_extensions.php';

// Create container for variables
$globals_container = new Core\GlobalsContainer();
$globals_container->set('twig', $twig);
$globals_container->set('session', $session);
$globals_container->set('email_settings', $email_settings);
$globals_container->set('global_vars', $global_vars);

// Create utils instance
$utils = new Core\Utils($session, $globals_container);
$globals_container->set('utils', $utils);

// Models container
$models = new Core\Models($dsn, $db_user, $db_password, $globals_container);
unset($dsn, $db_user, $db_password);
$globals_container->set('models', $models);