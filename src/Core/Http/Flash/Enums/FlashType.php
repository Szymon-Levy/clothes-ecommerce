<?php

namespace Core\Http\Flash\Enums;

enum FlashType: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
    case INFO = 'info';
    case WARNING = 'warning';
}