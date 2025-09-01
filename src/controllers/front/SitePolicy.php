<?php

namespace Controllers\front;

use Controllers\BaseController;

class SitePolicy extends BaseController
{

  public function privacyPolicy()
  {
    $data = [
      'shop_name'=> SHOP_NAME,
      'shop_address'=> SHOP_ADDRESS,
      'shop_email'=> SHOP_EMAIL,
      'shop_phone'=> SHOP_PHONE,
      'page_title'=> 'Privacy policy'
    ];

    $this->renderView('front/privacy-policy.html.twig', $data);
  }

  public function termsAndConditions()
  {
    $data = [
      'shop_name' => SHOP_NAME,
      'shop_address' => SHOP_ADDRESS,
      'shop_email' => SHOP_EMAIL,
      'shop_phone' => SHOP_PHONE,
      'domain' => FIXED_DOMAIN,
      'page_title' => 'Terms and conditions'
    ];

    $this->renderView('front/terms-and-conditions.html.twig', $data);
  }
}