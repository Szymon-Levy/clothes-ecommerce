<?php

namespace Core\ExcelExporter\Styler;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface ExcelStylerInterface
{
    public function style(Worksheet $sheet, int $headersCount);
}