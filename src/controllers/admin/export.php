<?php

$data_source = trim($_GET['data'] ?? '');

if ($data_source) {
  $export = new App\Export($data_source, $app);
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