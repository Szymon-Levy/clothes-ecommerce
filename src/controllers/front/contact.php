<?php

namespace Controllers\front;

use Controllers\BaseController;

class Contact extends BaseController
{

  public function index()
  {
    $data = [
      'shop_name' => SHOP_NAME,
      'shop_address' => SHOP_ADDRESS,
      'shop_email' => SHOP_EMAIL,
      'shop_phone' => SHOP_PHONE,
      'page_title' => 'Contact',
      'page_js' => 'contact'
    ];

    echo $this->twig->render('front/contact.html.twig', $data);
  }
}