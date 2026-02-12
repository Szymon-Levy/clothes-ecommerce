<?php

namespace Core\Factories;

use Core\ExcelExporter\Styler\ExcelBrandStyler;
use Core\ExcelExporter\XlsxBuilder\XlsxBuilder;

class XlsxBuilderFactory
{
    public function __construct(
        protected PhpSpreadsheetFactory $phpSpreadsheetFactory,
        protected ExcelBrandStyler $excelBrandStyler
    ){}

    public function makeBrandThemeExporter(): XlsxBuilder
    {
        return new XlsxBuilder(
            $this->phpSpreadsheetFactory,
            $this->excelBrandStyler
        );
    }
}