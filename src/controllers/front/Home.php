<?php

namespace Controllers\front;

use Controllers\BaseController;

class Home extends BaseController
{

  public function index()
  {
    $data = [
      'home_page' => true,
      'page_js' => 'home'
    ];

    echo $this->twig->render('front/index.html.twig', $data);
  }
}