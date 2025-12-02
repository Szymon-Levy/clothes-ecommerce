<?php

namespace Controllers;

use Core\Config\Config;
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
        protected Config $config,
        protected TemplateEngine $templateEngine,
        protected Request $request
    ){}

    protected function view(string $path, array $data = [])
    {
        return $this->templateEngine->render($path, $data);
    }
}