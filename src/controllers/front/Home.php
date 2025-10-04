<?php

namespace Controllers\front;

use Controllers\BaseController;

class Home extends BaseController
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