<?php

namespace App\Controllers;

use Core\Http\Request;
use Core\TemplateEngine\TemplateEngine;

abstract class BaseController
{
    public function __construct(
        private TemplateEngine $templateEngine,
        protected Request $request
    ){}

    protected function view(string $path, array $data = [])
    {
        return $this->templateEngine->render($path, $data);
    }
}