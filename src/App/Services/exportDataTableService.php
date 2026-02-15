<?php

namespace App\Services;

use Core\ExcelExporter\DTO\ExportTableDTO;
use Core\ExcelExporter\XlsxBuilder\XlsxBuilderFactory;

class exportDataTableService
{
    public function __construct(
        protected XlsxBuilderFactory $xlsxBuilderFactory
    ) {}

    public function getXlsxContent(ExportTableDTO $data, string $sheetTitle = 'Export data'): string
    {
        $xlsxBuilder = $this->xlsxBuilderFactory->makeBrandThemeExporter();

        return $xlsxBuilder->getXlsxContent($data, $sheetTitle);
    }
}