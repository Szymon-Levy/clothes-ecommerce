<?php

namespace Core\Utils;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportToXlsx
{
    protected array $data;
    protected string $file_name;
    protected array $output_data = [];
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
        $this->file_name = $data['file_name'] . '_' . date('d-m-Y-H-i-s');
        $this->output_data[] = $data['headings'];
    }

    private function prepareOutputData()
    {
        if ($this->data['db_data'] !== false) {
            foreach ($this->data['db_data'] as $row) {
                $line_data = [];

                foreach ($this->data['db_columns'] as $column_name) {
                    $line_data[] = $row[$column_name];
                }

                $this->output_data[] = $line_data;
            }
        }
    }

    private function styleWorksheet()
    {
        $this->sheet->setTitle('Exported data');

        $this->spreadsheet->getDefaultStyle()->getFont()->setName($this->styles['font']);

        $last_column_name = Coordinate::stringFromColumnIndex(count($this->data['headings']));
        $header_range = 'A1:' . $last_column_name . '1';

        $this->sheet->getStyle($header_range)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB($this->styles['header_background']);

        $this->sheet->getStyle($header_range)->getFont()->setName($this->styles['font']);
        $this->sheet->getStyle($header_range)->getFont()->setBold($this->styles['bold_header']);

        $this->sheet->getStyle($header_range)->getFont()->getColor()
            ->setARGB($this->styles['header_color']);

        foreach ($this->sheet->getColumnIterator() as $column) {
            $this->sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }

    public function exportData()
    {
        $this->prepareOutputData();

        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        $this->sheet->fromArray($this->output_data);

        $this->styleWorksheet();

        ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->file_name . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($this->spreadsheet);
        $writer->save('php://output');
        exit;
    }
}