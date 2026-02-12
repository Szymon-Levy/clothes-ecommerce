<?php

namespace App\Controllers;

use Core\Http\Request;
use Core\TemplateEngine\TemplateEngine;
use Core\Utils\Helpers;

abstract class BaseController
{
    public function __construct(
        private TemplateEngine $templateEngine,
        protected Request $request,
        protected Helpers $helpers
    ){}

    protected function view(string $path, array $data = []): string
    {
        return $this->templateEngine->render($path, $data);
    }
}