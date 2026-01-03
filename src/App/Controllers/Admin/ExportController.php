<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Core\Http\Response\RedirectResponse;
use Core\Utils\ExportToXlsx;
use Core\Utils\FlashMessage\FlashMessageAdmin;
use App\Services\ExportService;

class ExportController extends BaseController
{
    public function __construct(
        protected ExportService $exportService
    ){}

    public function export(FlashMessageAdmin $flashMessageAdmin)
    {
        $dataSource = $this->request->routeParam('data');

        $data = $this->exportService->getData($dataSource);

        if ($data === false) {
            $flashMessageAdmin->error('Unknown data source.');

            return new RedirectResponse('admin/newsletter');
        }

        $export = new ExportToXlsx($data);
        $export->exportData();
    }
}