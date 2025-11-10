<?php

use Core\Routing\Router;
use Controllers\ErrorsController;
use Controllers\Front\HomeController;
use Controllers\Front\ContactController;
use Controllers\Front\NewsletterController as FrontNewsletterController;
use Controllers\Front\UiElementsController;
use Controllers\Front\SitePolicyController;
use Controllers\Admin\NewsletterController as AdminNewsletterController;
use Controllers\Admin\DashboardController;
use Controllers\Admin\ExportController;

return function (Router $router) {
    // ERRORS
    $router->errorHandler(404, [ErrorsController::class, 'error404']);

    // FRONT
    $router->add('GET', '/', [HomeController::class, 'index']);

    $router->add('POST', '/ajax/newsletter-subscribe', [FrontNewsletterController::class, 'subscribe']);
    $router->add('GET', '/confirm-subscribtion/{token}', [FrontNewsletterController::class, 'confirmSubscribtion']);
    $router->add('GET', '/delete-subscribtion/{token}', [FrontNewsletterController::class, 'deleteSubscribtion']);

    $router->add('GET', '/contact', [ContactController::class, 'index']);
    $router->add('POST', '/ajax/contact-send-message', [ContactController::class, 'sendMessage']);

    $router->add('GET', '/privacy-policy', [SitePolicyController::class, 'privacyPolicy']);
    $router->add('GET', '/terms-and-conditions', [SitePolicyController::class, 'termsAndConditions']);

    // UI ELEMENTS
    $router->add('POST', '/ui_elements/subscribtion_popup', [UiElementsController::class, 'subscribtionPopup']);
    $router->add('POST', '/ui_elements/video_popup', [UiElementsController::class, 'videoPopup']);

    // ADMIN
    $router->add('GET', '/admin', [DashboardController::class, 'index']);
    $router->add('GET', '/admin/export/{data}', [ExportController::class, 'export']);

    $router->add('GET', '/admin/newsletter', [AdminNewsletterController::class, 'index']);
    $router->add('GET', '/admin/newsletter/add-subscriber', [AdminNewsletterController::class, 'addSubscriber']);
    $router->add('GET', '/admin/newsletter/edit-subscriber/{id}', [AdminNewsletterController::class, 'editSubscriber']);
    $router->add('POST', '/admin/ajax/add-subscriber', [AdminNewsletterController::class, 'addSubscriberToDB']);
    $router->add('POST', '/admin/ajax/delete-subscribers', [AdminNewsletterController::class, 'deleteSubscribers']);
    $router->add('POST', '/admin/ajax/edit-subscriber', [AdminNewsletterController::class, 'editSubscriberInDB']);
};