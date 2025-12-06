<?php

namespace Core\Utils\FlashMessage;

final class FlashMessageFront extends AbstractFlashMessage
{
    public function __construct()
    {
        $this->setType('front');
    }
}