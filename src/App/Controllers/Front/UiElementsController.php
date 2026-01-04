<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use Core\Http\Response\HtmlResponse;

final class UiElementsController extends BaseController
{

    public function subscribtionPopup()
    {
        $data = [
            'wrapper_class' => 'subscribtion-popup animate'
        ];

        return new HtmlResponse(
            $this->view('ui_elements/subscribtion_popup.html.twig', $data)
        );
    }

    public function videoPopup()
    {
        $data = [
            'wrapper_class' => 'popup--video',
            'video_name' => $this->request->post('video_name', null),
            'video_type' => $this->request->post('video_type', null)
        ];

        return new HtmlResponse(
            $this->view('ui_elements/video_popup.html.twig', $data)
        );
    }
}