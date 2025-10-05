<?php

namespace Controllers;

use Core\GlobalsContainer;
use Core\Routing\Router;
use Twig\Environment;
use Core\Models;
use Core\Session;
use Core\Utils;
use Core\Csrf;

abstract class BaseController
{
    protected Router $router;
    protected Environment $twig;
    protected Models $models;
    protected Session $session;
    protected array $email_settings;
    protected Utils $utils;
    protected array $global_vars;
    protected Csrf $csrf;

    public function __construct(GlobalsContainer $globals_container)
    {
        $this->router = $globals_container->get('router');
        $this->twig = $globals_container->get('twig');
        $this->models = $globals_container->get('models');
        $this->session = $globals_container->get('session');
        $this->email_settings = $globals_container->get('email_settings');
        $this->utils = $globals_container->get('utils');
        $this->global_vars = $globals_container->get('global_vars');
        $this->csrf = $globals_container->get('csrf');
    }

    protected function renderView(string $path, array $data = [])
    {
        echo $this->twig->render($path, $data);
    }

    protected function formSecurity(array $use_only = [])
    {
        if (empty($use_only) || in_array('csrf', $use_only)) {
            $csrf_error = $this->utils->isCsrfIncorrect();

            if ($csrf_error) {
                $response['error'] = $csrf_error;
                echo json_encode($response);
                exit;
            }

            $this->csrf->regenerateToken();
        }

        if (empty($use_only) || in_array('bot', $use_only)) {
            $bot_error = $this->utils->isFormFilledByBot();

            if ($bot_error) {
                $response['error'] = $bot_error;
                echo json_encode($response);
                exit;
            }
        }
    }
}