<?php

namespace Core;

use Core\Models;
use Core\PhpXlsxGenerator;

class Export
{
  private string $data_source;
  private null|array $data = null;
  private Models $models;

  public function __construct (string $data_source, Models $models)
  {
    $this->data_source = $data_source;
    $this->models = $models;
  }

  private function getTableData ():void
  {
    switch ($this->data_source) {
      case 'newsletter-subscribers':
        $this->data = $this->models->newsletter()->getExportSubscribersData();
        break;
    }
  }

  public function exportData ():bool
  {
    $this->getTableData();

    if (!$this->data) return false;

    $file_name = $this->data['file_name'] .'_' . date('d-m-Y-H-i-s') . ".xlsx";
    $excel_data[] = $this->data['headings'];

    if ($this->data['db_data']) {
      foreach ($this->data['db_data'] as $row) {
        $line_data = [];

        foreach ($this->data['db_columns'] as $column_name) {
          $line_data[] = $row[$column_name];
        }

        $excel_data[] = $line_data;
      }
    }
    
    $xlsx = PhpXlsxGenerator::fromArray($excel_data); 
    $xlsx->downloadAs($file_name);
    return true;
    exit;
  }
}