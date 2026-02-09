<?php

use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\NewsletterController;
use App\Middlewares\CsrfMiddleware;
use App\Middlewares\HoneypotMiddleware;
use Core\Router\Router;

return function (Router $router) {
    $router->group('/admin', function($router) {
        $router->get('', [DashboardController::class, 'index'])->name('admin-dashboard');
        
        $router->get('/newsletter', [NewsletterController::class, 'index'])->name('admin-newsletter.index');

        $router->get('/newsletter/add-subscriber', [NewsletterController::class, 'addSubscriber'])->name('admin-newsletter.addSubscriber');

        $router->get('/newsletter/edit-subscriber/{id}', [NewsletterController::class, 'editSubscriber'])->name('admin-newsletter.editSubscriber');

        $router->get('/newsletter/export-subscribers/', [NewsletterController::class, 'exportSubscribers']);

        $router->middleware([CsrfMiddleware::class, HoneypotMiddleware::class])
            ->post('/newsletter/subscriber/add', [NewsletterController::class, 'addSubscriberToDB']);

        $router->middleware([CsrfMiddleware::class])
            ->post('/newsletter/subscribers/delete', [NewsletterController::class, 'deleteSubscribers']);

        $router->middleware([CsrfMiddleware::class, HoneypotMiddleware::class])
            ->post('/newsletter/subscribers/update', [NewsletterController::class, 'editSubscriberInDB']);
    });
};