<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\ResponseInterface;
use Core\ValueObjects\Breadcrumbs;
use Core\ValueObjects\UrlSegments;

final class SitePolicyController extends BaseController
{

    public function privacyPolicy(): ResponseInterface
    {
        $urlSegments = UrlSegments::fromUri($this->request->uri())->get();
        $breadcrumbs = Breadcrumbs::fromSegments($urlSegments)->get();

        $data = [
            'page_title' => 'Privacy policy',
            'breadcrumbs' => $breadcrumbs
        ];

        return new HtmlResponse(
            $this->view('front/privacy-policy.html.twig', $data)
        );
    }

    public function termsAndConditions(): ResponseInterface
    {
        $urlSegments = UrlSegments::fromUri($this->request->uri())->get();
        $breadcrumbs = Breadcrumbs::fromSegments($urlSegments)->get();

        $data = [
            'page_title' => 'Terms and conditions',
            'breadcrumbs' => $breadcrumbs
        ];

        return new HtmlResponse(
            $this->view('front/terms-and-conditions.html.twig', $data)
        );
    }
}