<?php

namespace Controllers\Admin;

use Controllers\BaseController;
use Core\Utils\ExportToXlsx;
use Models\NewsletterModel;

class ExportController extends BaseController
{
    public function __construct(
        protected NewsletterModel $newsletterModel
    ){}

    private function getDataBySource(string $dataSource)
    {
        switch ($dataSource) {
            case 'newsletter-subscribers':
                return $this->newsletterModel->getSubscribersDataToExport();
                break;
            default:
                return false;
        }
    }

    public function export()
    {
        $dataSource = $this->router->current()->parameters()['data'] ?? '';

        $data = $this->getDataBySource($dataSource);

        if ($data === false) {
            $this->utils->showAdminMessage('Unknown data source.', 'error');
            $this->router->redirect('admin/newsletter');
        }

        $export = new ExportToXlsx($data);
        $export->exportData();

        echo '<script>window.self.close()</script>';
    }
}