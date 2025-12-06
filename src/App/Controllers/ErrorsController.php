<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Core\Http\Response\HtmlResponse;

class ErrorsController extends BaseController
{
    public function error404()
    {
        $data = [
            'error_page' => true,
        ];

        return new HtmlResponse(
            $this->view('front/errors/404.html.twig', $data), 
            404
        );
    }

    public function error405()
    {
        $data = [
            'error_page' => true,
        ];

        return new HtmlResponse(
            $this->view('front/errors/405.html.twig', $data), 
            405
        );
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

        return new HtmlResponse(
            $this->view('front/errors/500.html.twig', $data), 
            500
        );
    }
}