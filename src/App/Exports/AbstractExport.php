<?php

namespace App\Exports;

abstract class AbstractExport
{
    protected function addDateToFileName(string $name): string
    {
        return $name . '_' . date('d-m-Y-H-i-s') . 'sex';
    }
}