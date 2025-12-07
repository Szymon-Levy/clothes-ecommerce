<?php

namespace App\Controllers;

use Core\Http\Request;
use Core\TemplateEngine\TemplateEngine;
use Core\Utils\TemplateUrlPathManager;

abstract class BaseController
{
    public function __construct(
        protected TemplateEngine $templateEngine,
        protected Request $request,
        protected TemplateUrlPathManager $templateUrlPathManager
    ){}

    protected function view(string $path, array $data = [])
    {
        return $this->templateEngine->render($path, $data);
    }
}