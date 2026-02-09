<?php

use App\Controllers\Front\UiElementsController;
use Core\Router\Router;

return function (Router $router) {
    $router->post('/ui_elements/subscribtion_popup', [UiElementsController::class, 'subscribtionPopup']);
    $router->post('/ui_elements/video_popup', [UiElementsController::class, 'videoPopup']);
};