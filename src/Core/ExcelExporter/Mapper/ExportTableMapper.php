<?php

namespace Core\ExcelExporter\Mapper;

class ExportTableMapper
{
    public function map(array $whitelist, array $dbData): array
    {
        $result = [];

        foreach ($dbData as $row) {
            $mappedRow = [];

            foreach ($whitelist as $column) {
                if (! array_key_exists($column, $row)) {
                    continue;
                }

                $mappedRow[$column] = $row[$column];
            }

            $result[] = $mappedRow;
        }

        return $result;
    }
}