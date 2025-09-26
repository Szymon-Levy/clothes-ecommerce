<?php

namespace Controllers\admin;

use Controllers\BaseController;

class Export extends BaseController
{
  public function export()
  {
    $data_source = $this->router->current()->parameters()['data'] ?? null;

    if ($data_source) {
      $export = new \Core\Export($data_source, $this->models);
      $export_data = $export->exportData();

      if (!$export_data) {
        echo '<script>';
        echo 'window.self.close()';
        echo '</script>';
      }
    }
    else {
      $this->utils->redirect('admin');
    }
  }
}