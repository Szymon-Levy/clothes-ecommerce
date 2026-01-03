<?php

namespace App\Exports;

use App\Models\NewsletterModel;

class NewsletterExport
{
    public function __construct(
        protected NewsletterModel $newsletterModel
    ){}

    public function subscribersData()
    {
        $data = [
            'file_name' => 'newsletter-subscribers',
            'headings' => ['ID', 'SUBSCRIBER NAME', 'EMAIL', 'CREATED DATE', 'ACTIVITY STATUS'],
            'db_columns' => ['id', 'name', 'email', 'created_at', 'activity_status'],
            'db_data' => $this->newsletterModel->getSubscribersExportData()
        ];

        return $data;
    }
}