<?php

namespace App\Exports;

use App\Models\NewsletterModel;
use Core\ExcelExporter\DTO\ExportTableDTO;

final class NewsletterExport extends BaseExport
{
    public function __construct(
        protected NewsletterModel $newsletterModel
    ){}

    public function subscribersData(): ExportTableDTO
    {
        $headers = ['ID', 'SUBSCRIBER NAME', 'EMAIL', 'CREATED DATE', 'ACTIVITY STATUS'];

        $columnsWhitelist = ['id', 'name', 'email', 'created_at', 'activity_status'];

        $dbRows = $this->newsletterModel->getSubscribersExportData();

        $mappedData = $this->exportTableMapper->map($columnsWhitelist, $dbRows);

        return new ExportTableDTO(
            headers: $headers,
            rows: $mappedData
        );
    }
}