<?php

namespace Core\Utils;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class XlsxBuilder
{
    protected array $data;
    protected array $outputData = [];
    protected Spreadsheet $spreadsheet;
    protected Worksheet $sheet;
    protected array $styles = [
        'font' => 'Aptos',
        'bold_header' => true,
        'header_background' => '155bda',
        'header_color' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE
    ];

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->outputData[] = $data['headings'];
    }

    protected function prepareOutputData()
    {
        if ($this->data['db_data'] !== false) {
            foreach ($this->data['db_data'] as $row) {
                $lineData = [];

                foreach ($this->data['db_columns'] as $columnName) {
                    $lineData[] = $row[$columnName];
                }

                $this->outputData[] = $lineData;
            }
        }
    }

    protected function styleWorksheet()
    {
        $this->sheet->setTitle('Exported data');

        $this->spreadsheet->getDefaultStyle()->getFont()->setName($this->styles['font']);

        $lastColumnName = Coordinate::stringFromColumnIndex(count($this->data['headings']));
        $headerRange = 'A1:' . $lastColumnName . '1';

        $this->sheet->getStyle($headerRange)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB($this->styles['header_background']);

        $this->sheet->getStyle($headerRange)->getFont()->setName($this->styles['font']);
        $this->sheet->getStyle($headerRange)->getFont()->setBold($this->styles['bold_header']);

        $this->sheet->getStyle($headerRange)->getFont()->getColor()
            ->setARGB($this->styles['header_color']);

        foreach ($this->sheet->getColumnIterator() as $column) {
            $this->sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }

    public function generateXlsxContent(): string
    {
        $this->prepareOutputData();

        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        $this->sheet->fromArray($this->outputData);

        $this->styleWorksheet();

        ob_start();

        $writer = new Xlsx($this->spreadsheet);
        $writer->save('php://output');
        
        $content = ob_get_clean();

        return $content;
    }
}