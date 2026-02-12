<?php

namespace Core\ExcelExporter\DTO;

class ExportTableDTO
{
    public function __construct(
        public array $headers,
        public array $rows
    ){}
}