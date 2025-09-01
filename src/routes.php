<?php

use Core\Routing\Router;
use Controllers\Errors;
use Controllers\front\Home;
use Controllers\front\Contact;
use Controllers\front\Newsletter;
use Controllers\front\UiElements;
use Controllers\front\SitePolicy;

return function(Router $router) {
  // ERRORS
  $router->errorHandler(404, [Errors::class, 'error404']);

  // FRONT
  $router->add('GET', '/', [Home::class, 'index']);

  $router->add('POST', '/ajax/newsletter-subscribe', [Newsletter::class, 'subscribe']);
  $router->add('GET', '/confirm-subscribtion/{token}', [Newsletter::class, 'confirmSubscribtion']);
  $router->add('GET', '/delete-subscribtion/{token}', [Newsletter::class, 'deleteSubscribtion']);

  $router->add('GET', '/contact', [Contact::class, 'index']);
  $router->add('POST', '/ajax/contact-send-message', [Contact::class, 'sendMessage']);

  $router->add('GET', '/privacy-policy', [SitePolicy::class, 'privacyPolicy']);
  $router->add('GET', '/terms-and-conditions', [SitePolicy::class, 'termsAndConditions']);
  
  // UI ELEMENTS
  
  $router->add('POST', '/ui_elements/newsletter_popup', [UiElements::class, 'newsletterPopup']);
  $router->add('POST', '/ui_elements/video_popup', [UiElements::class, 'videoPopup']);

  // ADMIN
};