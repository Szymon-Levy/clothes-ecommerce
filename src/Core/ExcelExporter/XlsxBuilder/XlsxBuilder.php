<?php

namespace Core\ExcelExporter\XlsxBuilder;

use Core\ExcelExporter\DTO\ExportTableDTO;
use Core\ExcelExporter\Styler\ExcelStylerInterface;
use Core\Factories\PhpSpreadsheetFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class XlsxBuilder
{
    public function __construct(
        protected PhpSpreadsheetFactory $phpSpreadsheetFactory,
        protected ExcelStylerInterface $styler
    ) {}

    public function getXlsxContent(ExportTableDTO $dataTable, string $sheetTitle): string
    {
        $spreadsheet = $this->phpSpreadsheetFactory->makeSpreadsheet();
        
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle($sheetTitle);

        $worksheetData = [$dataTable->headers, ...$dataTable->rows];

        $sheet->fromArray($worksheetData);

        $headingsCount = count($dataTable->headers);

        $this->styler->style($sheet, $headingsCount);

        return $this->writeToString($spreadsheet);
    }

    protected function writeToString(Spreadsheet $spreadsheet): string
    {
        $writer = $this->phpSpreadsheetFactory->makeXlsxWriter($spreadsheet);

        ob_start();

        $writer->save('php://output');
        
        return ob_get_clean();
    }
}