<?php

namespace App\Exports;

use Core\Utils\Helpers;

abstract class BaseExport
{
    public function __construct(
        protected Helpers $helpers
    ){}
}