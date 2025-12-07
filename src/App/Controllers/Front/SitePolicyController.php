<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use Core\Http\Response\HtmlResponse;

class SitePolicyController extends BaseController
{

    public function privacyPolicy()
    {
        $data = [
            'page_title' => 'Privacy policy'
        ];

        $this->templateUrlPathManager->saveData();

        return new HtmlResponse(
            $this->view('front/privacy-policy.html.twig', $data)
        );
    }

    public function termsAndConditions()
    {
        $data = [
            'page_title' => 'Terms and conditions'
        ];

        $this->templateUrlPathManager->saveData();

        return new HtmlResponse(
            $this->view('front/terms-and-conditions.html.twig', $data)
        );
    }
}