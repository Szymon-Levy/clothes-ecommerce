<?php

namespace App\Exports;

use Core\ExcelExporter\Mapper\ExportTableMapper;

abstract class BaseExport
{
    public function __construct(
        protected ExportTableMapper $exportTableMapper
    ){}
}