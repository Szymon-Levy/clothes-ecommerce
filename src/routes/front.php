<?php

use App\Controllers\Front\ContactController;
use App\Controllers\Front\HomeController;
use App\Controllers\Front\NewsletterController;
use App\Controllers\Front\SitePolicyController;
use App\Middlewares\CsrfMiddleware;
use App\Middlewares\HoneypotMiddleware;
use Core\Router\Router;

return function (Router $router) {
    $router->get('/', [HomeController::class, 'index'])->name('home');

    $router->middleware([CsrfMiddleware::class, HoneypotMiddleware::class])
        ->post('/newsletter/subscriber/add', [NewsletterController::class, 'subscribe']);

    $router->get('/confirm-subscribtion/{token}', [NewsletterController::class, 'confirmSubscribtion']);

    $router->get('/delete-subscribtion/{token}', [NewsletterController::class, 'deleteSubscribtion']);

    $router->get('/contact', [ContactController::class, 'index'])->name('contact');

    $router->middleware([CsrfMiddleware::class, HoneypotMiddleware::class])
        ->post('/contact/send-message', [ContactController::class, 'sendMessage']);

    $router->get('/privacy-policy', [SitePolicyController::class, 'privacyPolicy'])->name('privacyPolicy');

    $router->get('/terms-and-conditions', [SitePolicyController::class, 'termsAndConditions'])->name('termsAndConditions');
};