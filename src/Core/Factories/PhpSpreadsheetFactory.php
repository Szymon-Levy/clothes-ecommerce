<?php

namespace Core\Factories;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PhpSpreadsheetFactory
{
    public function makeSpreadsheet(): Spreadsheet
    {
        return new Spreadsheet();
    }

    public function makeXlsxWriter(Spreadsheet $spreadsheet): Xlsx
    {
        return new Xlsx($spreadsheet);
    }
}