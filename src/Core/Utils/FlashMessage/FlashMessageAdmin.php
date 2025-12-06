<?php

namespace Core\Utils\FlashMessage;

final class FlashMessageAdmin extends AbstractFlashMessage
{
    public function __construct()
    {
        $this->setType('admin');
    }
}