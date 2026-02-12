<?php

namespace Core\ExcelExporter\Styler;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

final class ExcelBrandStyler implements ExcelStylerInterface
{
    protected const FONT = 'Aptos';
    protected const BOLD_HEADER = true;
    protected const HEADER_COLOR = Color::COLOR_WHITE;
    protected const HEADER_BACKGROUND = 'FF155BDA';

    public function style(Worksheet $sheet, int $headersCount)
    {
        $this->styleHeader($sheet, $headersCount);
        $this->autosizeColumns($sheet);
        $this->fixHeaderAtTop($sheet);
    }

    protected function styleHeader(Worksheet $sheet, int $headersCount): void
    {
        $lastColumn = Coordinate::stringFromColumnIndex($headersCount);

        $range = "A1:{$lastColumn}1";

        $style = $sheet->getStyle($range);

        $style->getFont()
            ->setBold(self::BOLD_HEADER)
            ->setName(self::FONT)
            ->getColor()
            ->setARGB(self::HEADER_COLOR);

        $style->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB(self::HEADER_BACKGROUND);
    }

    protected function autosizeColumns(Worksheet $sheet): void
    {
        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }

    protected function fixHeaderAtTop(Worksheet $sheet): void
    {
        $sheet->freezePane('A2');
    }
}