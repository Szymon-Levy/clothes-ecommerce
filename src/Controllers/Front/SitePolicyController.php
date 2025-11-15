<?php

namespace Controllers\Front;

use Controllers\BaseController;

class SitePolicyController extends BaseController
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