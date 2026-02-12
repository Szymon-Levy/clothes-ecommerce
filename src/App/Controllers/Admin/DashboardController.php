<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\ResponseInterface;
use Core\ValueObjects\Breadcrumbs;
use Core\ValueObjects\UrlSegments;

final class DashboardController extends BaseController
{
    public function index(): ResponseInterface
    {
        $urlSegments = UrlSegments::fromUri($this->request->uri())->get();

        $breadcrumbs = Breadcrumbs::fromSegments($urlSegments)
            ->editAtPosition(0, ['name' => 'Dashboard'])
            ->get();

        $data = [
            'page_title' => 'Dashboard',
            'breadcrumbs' => $breadcrumbs
        ];

        return new HtmlResponse(
            $this->view('admin/dashboard.html.twig', $data)
        );
    }
}