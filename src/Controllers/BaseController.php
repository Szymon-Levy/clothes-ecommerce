<?php

namespace Controllers;

use Core\Config\Config;
use Core\Http\Csrf;
use Core\Http\Request;
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
        protected TemplateEngine $templateEngine,
        protected Request $request
    ){}

    protected function renderView(string $path, array $data = [])
    {
        echo $this->templateEngine->render($path, $data);
    }

    protected function formSecurity(array $useOnly = [])
    {
        if (empty($useOnly) || in_array('csrf', $useOnly)) {
            $csrfError = $this->utils->isCsrfIncorrect();

            if ($csrfError) {
                $response['error'] = $csrfError;
                echo json_encode($response);
                exit;
            }
        }

        if (empty($useOnly) || in_array('bot', $useOnly)) {
            $botError = $this->utils->isFormFilledByBot();

            if ($botError) {
                $response['error'] = $botError;
                echo json_encode($response);
                exit;
            }
        }
    }
}