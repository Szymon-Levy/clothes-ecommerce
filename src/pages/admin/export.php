<?php

$data_source = isset($_GET['data']) ? strip_tags($_GET['data']) : null;

if ($data_source) {
  $export = new ClothesEcommerce\App\Export($data_source, $app);
  $export_data = $export->exportData();

  if (!$export_data) {
    echo '<script>';
    echo 'window.self.close()';
    echo '</script>';
  }
}
else {
  redirect('admin');
}