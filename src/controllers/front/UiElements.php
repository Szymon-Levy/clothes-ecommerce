<?php

namespace Controllers\front;

use Controllers\BaseController;

class UiElements extends BaseController
{

    public function subscribtionPopup()
    {
        $data = [
            'wrapper_class' => 'subscribtion-popup animate'
        ];

        echo $this->renderView('ui_elements/subscribtion_popup.html.twig', $data);
    }

    public function videoPopup()
    {
        $data = [
            'wrapper_class' => 'popup--video',
            'video_name' => $_POST['video_name'] ?? null,
            'video_type' => $_POST['video_type'] ?? null
        ];

        echo $this->renderView('ui_elements/video_popup.html.twig', $data);
    }
}