<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use Core\Http\Response\HtmlResponse;
use Core\ValueObjects\Breadcrumbs;
use Core\ValueObjects\UrlSegments;

final class HomeController extends BaseController
{

    public function index()
    {
        $urlSegments = UrlSegments::fromUri($this->request->uri())->get();
        $breadcrumbs = Breadcrumbs::fromSegments($urlSegments)->get();

        $data = [
            'home_page' => true,
            'page_js' => 'home',
            'breadcrumbs' => $breadcrumbs
        ];

        return new HtmlResponse(
            $this->view('front/index.html.twig', 
            $data)
        );
    }
}