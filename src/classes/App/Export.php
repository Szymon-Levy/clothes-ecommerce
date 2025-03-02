<?php

namespace ClothesEcommerce\App;

class Export 
{
  private string $data_source;
  private null|array $data = null;
  private \ClothesEcommerce\App\App $app;

  public function __construct (string $data, \ClothesEcommerce\App\App $app)
  {
    $this->data_source = $data;
    $this->app = $app;
  }

  private function getTableData ():void
  {
    switch ($this->data_source) {
      case 'newsletter-subscribers':
        $this->data = $this->app->newsletter()->getExportSubscribersData();
        break;
    }
  }

  public function exportData ():bool
  {
    $this->getTableData();

    if (!$this->data) return false;

    $file_name = $this->data['file_name'] .'_' . date('d-m-Y') . ".xlsx";
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