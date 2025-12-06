<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use Core\Http\Response\HtmlResponse;

class HomeController extends BaseController
{

    public function index()
    {
        $data = [
            'home_page' => true,
            'page_js' => 'home'
        ];

        return new HtmlResponse(
            $this->view('front/index.html.twig', 
            $data)
        );
    }
}