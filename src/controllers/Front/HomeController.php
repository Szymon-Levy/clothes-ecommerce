<?php

namespace Controllers\Front;

use Controllers\BaseController;

class HomeController extends BaseController
{

    public function index()
    {
        $data = [
            'home_page' => true,
            'page_js' => 'home'
        ];

        $this->renderView('front/index.html.twig', $data);
    }
}