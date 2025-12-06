<?php

use Core\Router\Router;
use App\Controllers\Front\HomeController;
use App\Controllers\Front\ContactController;
use App\Controllers\Front\NewsletterController as FrontNewsletterController;
use App\Controllers\Front\UiElementsController;
use App\Controllers\Front\SitePolicyController;
use App\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ExportController;
use App\Middlewares\CsrfMiddleware;
use App\Middlewares\HoneypotMiddleware;

return function (Router $router) {
    // FRONT
    $router->get('/', [HomeController::class, 'index']);

    $router->middleware([CsrfMiddleware::class, HoneypotMiddleware::class])
        ->post('/newsletter/subscriber/add', [FrontNewsletterController::class, 'subscribe']);

    $router->get('/confirm-subscribtion/{token}', [FrontNewsletterController::class, 'confirmSubscribtion']);

    $router->get('/delete-subscribtion/{token}', [FrontNewsletterController::class, 'deleteSubscribtion']);

    $router->get('/contact', [ContactController::class, 'index']);

    $router->middleware([CsrfMiddleware::class, HoneypotMiddleware::class])
        ->post('/contact/send-message', [ContactController::class, 'sendMessage']);

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

        $router->middleware([CsrfMiddleware::class, HoneypotMiddleware::class])
            ->post('/newsletter/subscriber/add', [AdminNewsletterController::class, 'addSubscriberToDB']);

        $router->middleware([CsrfMiddleware::class])
            ->post('/newsletter/subscribers/delete', [AdminNewsletterController::class, 'deleteSubscribers']);

        $router->middleware([CsrfMiddleware::class, HoneypotMiddleware::class])
            ->post('/newsletter/subscribers/update', [AdminNewsletterController::class, 'editSubscriberInDB']);
    });
};