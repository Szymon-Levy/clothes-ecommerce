<?php

namespace Controllers\front;

use Controllers\BaseController;

class UiElements extends BaseController
{

    public function newsletterPopup()
    {
        $data = [
            'wrapper_class' => 'newsletter-popup animate'
        ];

        echo $this->twig->render('ui_elements/newsletter_popup.html.twig', $data);
    }

    public function videoPopup()
    {
        $data = [
            'wrapper_class' => 'popup--video',
            'video_name' => $_POST['video_name'] ?? null,
            'video_type' => $_POST['video_type'] ?? null
        ];

        echo $this->twig->render('ui_elements/video_popup.html.twig', $data);
    }
}