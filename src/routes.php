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
    $router->errorHandler(400, [ErrorsController::class, 'error400']);
    $router->errorHandler(404, [ErrorsController::class, 'error404']);
    $router->errorHandler(500, [ErrorsController::class, 'error500']);

    // FRONT
    $router->get('/', [HomeController::class, 'index']);

    $router->post('/ajax/newsletter-subscribe', [FrontNewsletterController::class, 'subscribe']);
    $router->get('/confirm-subscribtion/{token}', [FrontNewsletterController::class, 'confirmSubscribtion']);
    $router->get('/delete-subscribtion/{token}', [FrontNewsletterController::class, 'deleteSubscribtion']);

    $router->get('/contact', [ContactController::class, 'index']);
    $router->post('/ajax/contact-send-message', [ContactController::class, 'sendMessage']);

    $router->get('/privacy-policy', [SitePolicyController::class, 'privacyPolicy']);
    $router->get('/terms-and-conditions', [SitePolicyController::class, 'termsAndConditions']);

    // UI ELEMENTS
    $router->post('/ui_elements/subscribtion_popup', [UiElementsController::class, 'subscribtionPopup']);
    $router->post('/ui_elements/video_popup', [UiElementsController::class, 'videoPopup']);

    // ADMIN
    $router->group('/admin', function($router) {
        $router->get('', [DashboardController::class, 'index']);
        
        $router->get('/export/{data}', [ExportController::class, 'export']);
        
        $router->get('/newsletter', [AdminNewsletterController::class, 'index']);
        $router->get('/newsletter/add-subscriber', [AdminNewsletterController::class, 'addSubscriber']);
        $router->get('/newsletter/edit-subscriber/{id}', [AdminNewsletterController::class, 'editSubscriber']);
        $router->post('/ajax/add-subscriber', [AdminNewsletterController::class, 'addSubscriberToDB']);
        $router->post('/ajax/delete-subscribers', [AdminNewsletterController::class, 'deleteSubscribers']);
        $router->post('/ajax/edit-subscriber', [AdminNewsletterController::class, 'editSubscriberInDB']);
    });
};