<?php

namespace App\Exports;

use App\Models\NewsletterModel;

final class NewsletterExport extends BaseExport
{
    public function __construct(
        protected NewsletterModel $newsletterModel
    ){}

    public function subscribersData()
    {
        $data = [
            'file_name' => $this->addDateToFileName('newsletter-subscribers'),
            'headings' => ['ID', 'SUBSCRIBER NAME', 'EMAIL', 'CREATED DATE', 'ACTIVITY STATUS'],
            'db_columns' => ['id', 'name', 'email', 'created_at', 'activity_status'],
            'db_data' => $this->newsletterModel->getSubscribersExportData()
        ];

        return $data;
    }
}