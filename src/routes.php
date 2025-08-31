<?php

use Core\Routing\Router;
use Controllers\front\Home;
use Controllers\front\Contact;
use Controllers\front\UiElements;

return function(Router $router) {
  // FRONT
  $router->add('GET', '/', [Home::class, 'index']);
  $router->add('GET', '/contact', [Contact::class, 'index']);
  
  // UI ELEMENTS
  
  $router->add('POST', '/ui_elements/newsletter_popup', [UiElements::class, 'newsletterPopup']);
  $router->add('POST', '/ui_elements/video_popup', [UiElements::class, 'videoPopup']);

  // ADMIN
};