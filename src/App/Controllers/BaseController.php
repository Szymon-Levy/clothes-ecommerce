<?php

namespace App\Controllers;

use Core\Http\Flash\FlashService;
use Core\Http\Request;
use Core\TemplateEngine\TemplateEngine;

abstract class BaseController
{
    public function __construct(
        protected TemplateEngine $templateEngine,
        protected Request $request,
        protected FlashService $flash
    ){}

    protected function view(string $path, array $data = []): string
    {
        return $this->templateEngine->render($path, $data);
    }
}