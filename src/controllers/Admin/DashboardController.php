<?php

namespace Controllers\Admin;

use Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $data = [
            'page_title' => 'Dashboard'
        ];

        $this->renderView('admin/dashboard.html.twig', $data);
    }
}