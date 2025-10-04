<?php

namespace Controllers\front;

use Controllers\BaseController;

class SitePolicy extends BaseController
{

    public function privacyPolicy()
    {
        $data = [
            'page_title' => 'Privacy policy'
        ];

        $this->renderView('front/privacy-policy.html.twig', $data);
    }

    public function termsAndConditions()
    {
        $data = [
            'page_title' => 'Terms and conditions'
        ];

        $this->renderView('front/terms-and-conditions.html.twig', $data);
    }
}