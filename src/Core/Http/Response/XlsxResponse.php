<?php

namespace Core\Http\Response;

final class XlsxResponse extends AbstractDownloadResponse
{
    public function __construct(
        string $content,
        string $attachmentName,
    )
    {
        $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

        parent::__construct(
            $content, 
            $this->makeFullFileName($attachmentName), 
            $contentType
        );
    }

    protected function makeFullFileName(string $name): string
    {
        if (str_ends_with(strtolower($name) ,'.xlsx')) {
            return $name;
        }
        
        return $name . '.xlsx';
    }
}