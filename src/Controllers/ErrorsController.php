<?php

namespace Controllers;

use Controllers\BaseController;

class ErrorsController extends BaseController
{
    public function error400()
    {
        $data = [
            'error_page' => true,
        ];

        $this->renderView('front/errors/400.html.twig', $data);
    }

    public function error404()
    {
        $data = [
            'error_page' => true,
        ];

        $this->renderView('front/errors/404.html.twig', $data);
    }

    public function error500(\Throwable $e)
    {
        $data = [
            'error_page' => true,
            'error_message' => $e->getMessage(),
            'error_file' => $e->getFile(),
            'error_line' => $e->getLine(),
            'error_type' => get_class($e),
            'error_trace' => preg_split('/\s*#\s*/', trim($e->getTraceAsString()), -1, PREG_SPLIT_NO_EMPTY),
        ];

        $this->renderView('front/errors/500.html.twig', $data);
    }
}