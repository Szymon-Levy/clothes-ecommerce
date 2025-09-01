<?php

use Core\Routing\Router;
use Controllers\Errors;
use Controllers\front\Home;
use Controllers\front\Contact;
use Controllers\front\UiElements;

return function(Router $router) {
  // ERRORS
  $router->errorHandler(404, [Errors::class, 'error404']);

  // FRONT
  $router->add('GET', '/', [Home::class, 'index']);
  $router->add('GET', '/contact', [Contact::class, 'index']);
  $router->add('POST', '/ajax/contact-send-message', [Contact::class, 'sendMessage']);
  
  // UI ELEMENTS
  
  $router->add('POST', '/ui_elements/newsletter_popup', [UiElements::class, 'newsletterPopup']);
  $router->add('POST', '/ui_elements/video_popup', [UiElements::class, 'videoPopup']);

  // ADMIN
};