<?php

namespace App\Services;

use App\Exports\NewsletterExport;

class ExportService
{
    public function __construct(
        protected NewsletterExport $newsletterExport
    ){}

    protected function exports(): array
    {
        return [
            'newsletter-subscribers' => fn() => $this->newsletterExport->subscribersData()
        ];
    }

    public function getData(string $source): mixed
    {
        $exports = $this->exports();

        if (isset($exports[$source])) {
            return $exports[$source]();
        }

        return false;
    }
}