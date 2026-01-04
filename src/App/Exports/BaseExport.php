<?php

namespace App\Exports;

abstract class BaseExport
{
    protected function addDateToFileName(string $name): string
    {
        return $name . '_' . date('d-m-Y-H-i-s') . 'sex';
    }
}