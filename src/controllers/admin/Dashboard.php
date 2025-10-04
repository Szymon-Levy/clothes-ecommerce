<?php

namespace Controllers\admin;

use Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'page_title' => 'Dashboard'
        ];

        $this->renderView('admin/dashboard.html.twig', $data);
    }
}