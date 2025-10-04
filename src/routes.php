<?php

use Core\Routing\Router;
use Controllers\Errors;
use Controllers\front\Home;
use Controllers\front\Contact;
use Controllers\front\Newsletter as FrontNewsletter;
use Controllers\front\UiElements;
use Controllers\front\SitePolicy;
use Controllers\admin\Newsletter as AdminNewsletter;
use Controllers\admin\Dashboard;
use Controllers\admin\Export;

return function (Router $router) {
    // ERRORS
    $router->errorHandler(404, [Errors::class, 'error404']);

    // FRONT
    $router->add('GET', '/', [Home::class, 'index']);

    $router->add('POST', '/ajax/newsletter-subscribe', [FrontNewsletter::class, 'subscribe']);
    $router->add('GET', '/confirm-subscribtion/{token}', [FrontNewsletter::class, 'confirmSubscribtion']);
    $router->add('GET', '/delete-subscribtion/{token}', [FrontNewsletter::class, 'deleteSubscribtion']);

    $router->add('GET', '/contact', [Contact::class, 'index']);
    $router->add('POST', '/ajax/contact-send-message', [Contact::class, 'sendMessage']);

    $router->add('GET', '/privacy-policy', [SitePolicy::class, 'privacyPolicy']);
    $router->add('GET', '/terms-and-conditions', [SitePolicy::class, 'termsAndConditions']);

    // UI ELEMENTS
    $router->add('POST', '/ui_elements/newsletter_popup', [UiElements::class, 'newsletterPopup']);
    $router->add('POST', '/ui_elements/video_popup', [UiElements::class, 'videoPopup']);

    // ADMIN
    $router->add('GET', '/admin', [Dashboard::class, 'index']);
    $router->add('GET', '/admin/export/{data}', [Export::class, 'export']);

    $router->add('GET', '/admin/newsletter', [AdminNewsletter::class, 'index']);
    $router->add('GET', '/admin/newsletter/add-subscriber', [AdminNewsletter::class, 'addSubscriber']);
    $router->add('GET', '/admin/newsletter/edit-subscriber/{id}', [AdminNewsletter::class, 'editSubscriber']);
    $router->add('POST', '/admin/ajax/add-subscriber', [AdminNewsletter::class, 'addSubscriberToDB']);
    $router->add('POST', '/admin/ajax/delete-subscribers', [AdminNewsletter::class, 'deleteSubscribers']);
    $router->add('POST', '/admin/ajax/edit-subscriber', [AdminNewsletter::class, 'editSubscriberInDB']);
};