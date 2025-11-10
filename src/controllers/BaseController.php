<?php

namespace Controllers;

use Core\Config\Config;
use Core\Http\Csrf;
use Core\Http\Session;
use Core\Routing\Router;
use Core\TemplateEngine\TemplateEngine;
use Core\Utils\Utils;

abstract class BaseController
{
    public function __construct(
        protected Router $router,
        protected Session $session,
        protected Utils $utils,
        protected Csrf $csrf,
        protected Config $config,
        protected TemplateEngine $template_engine
    ){}

    protected function renderView(string $path, array $data = [])
    {
        echo $this->template_engine->engine()->render($path, $data);
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