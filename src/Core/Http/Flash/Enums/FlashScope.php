<?php

namespace Core\Http\Flash\Enums;

enum FlashScope: string
{
    case FRONT = 'front';
    case ADMIN = 'admin';
}