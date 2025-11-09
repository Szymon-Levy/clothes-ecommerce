<?php

namespace Controllers;

use Controllers\BaseController;

class Errors extends BaseController
{

    public function error404()
    {
        $data = [
            'page_404' => true,
        ];

        echo $this->renderView('front/404.html.twig', $data);
    }
}