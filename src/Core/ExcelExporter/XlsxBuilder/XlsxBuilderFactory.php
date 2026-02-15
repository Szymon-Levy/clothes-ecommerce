<?php

namespace Core\ExcelExporter\XlsxBuilder;

use Core\ExcelExporter\PhpSpreadsheetFactory\PhpSpreadsheetFactory;
use Core\ExcelExporter\Styler\ExcelBrandStyler;

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