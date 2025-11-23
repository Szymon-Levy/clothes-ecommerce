<?php

use Core\Routing\Router;
use Controllers\Front\HomeController;
use Controllers\Front\ContactController;
use Controllers\Front\NewsletterController as FrontNewsletterController;
use Controllers\Front\UiElementsController;
use Controllers\Front\SitePolicyController;
use Controllers\Admin\NewsletterController as AdminNewsletterController;
use Controllers\Admin\DashboardController;
use Controllers\Admin\ExportController;

return function (Router $router) {
    // FRONT
    $router->get('/', [HomeController::class, 'index']);

    $router->post('/newsletter/subscriber/add', [FrontNewsletterController::class, 'subscribe']);
    $router->get('/confirm-subscribtion/{token}', [FrontNewsletterController::class, 'confirmSubscribtion']);
    $router->get('/delete-subscribtion/{token}', [FrontNewsletterController::class, 'deleteSubscribtion']);

    $router->get('/contact', [ContactController::class, 'index']);
    $router->post('/contact/send-message', [ContactController::class, 'sendMessage']);

    $router->get('/privacy-policy', [SitePolicyController::class, 'privacyPolicy']);
    $router->get('/terms-and-conditions', [SitePolicyController::class, 'termsAndConditions']);

    // UI ELEMENTS
    $router->post('/ui_elements/subscribtion_popup', [UiElementsController::class, 'subscribtionPopup']);
    $router->post('/ui_elements/video_popup', [UiElementsController::class, 'videoPopup']);

    // ADMIN
    $router->middleware(['admin'])->group('/admin', function($router) {
        $router->get('', [DashboardController::class, 'index']);
        
        $router->get('/export/{data}', [ExportController::class, 'export']);
        
        $router->get('/newsletter', [AdminNewsletterController::class, 'index']);
        $router->get('/newsletter/add-subscriber', [AdminNewsletterController::class, 'addSubscriber']);
        $router->get('/newsletter/edit-subscriber/{id}', [AdminNewsletterController::class, 'editSubscriber']);
        $router->post('/newsletter/subscriber/add', [AdminNewsletterController::class, 'addSubscriberToDB']);
        $router->post('/newsletter/subscribers/delete', [AdminNewsletterController::class, 'deleteSubscribers']);
        $router->post('/newsletter/subscribers/update', [AdminNewsletterController::class, 'editSubscriberInDB']);
    });
};