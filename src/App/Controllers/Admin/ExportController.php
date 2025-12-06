<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Core\Http\Response\RedirectResponse;
use Core\Utils\ExportToXlsx;
use Core\Utils\FlashMessage\FlashMessageAdmin;
use App\Models\NewsletterModel;

class ExportController extends BaseController
{
    public function __construct(
        protected NewsletterModel $newsletterModel
    ){}

    protected function getDataBySource(string $dataSource)
    {
        switch ($dataSource) {
            case 'newsletter-subscribers':
                return $this->newsletterModel->getSubscribersDataToExport();
                break;
            default:
                return false;
        }
    }

    public function export(FlashMessageAdmin $flashMessageAdmin)
    {
        $dataSource = $this->request->routeParam('data');

        $data = $this->getDataBySource($dataSource);

        if ($data === false) {
            $flashMessageAdmin->error('Unknown data source.');

            return new RedirectResponse('admin/newsletter');
        }

        $export = new ExportToXlsx($data);
        $export->exportData();

        echo '<script>window.self.close()</script>';
    }
}