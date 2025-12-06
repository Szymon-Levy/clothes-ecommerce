<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Core\Http\Response\HtmlResponse;

class DashboardController extends BaseController
{
    public function index()
    {
        $data = [
            'page_title' => 'Dashboard'
        ];

        return new HtmlResponse(
            $this->view('admin/dashboard.html.twig', $data)
        );
    }
}