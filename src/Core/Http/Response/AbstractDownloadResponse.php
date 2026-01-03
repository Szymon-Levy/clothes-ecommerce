<?php

namespace Core\Http\Response;

abstract class AbstractDownloadResponse extends AbstractResponse
{
    public function __construct(
        string $content,
        string $attachmentName,
        string $contentType = ''
    )
    {
        parent::__construct($content, 200);

        $this->setHeader('Content-Type', $contentType);
        $this->setHeader('Content-Disposition', 'attachment; filename="' . $attachmentName . '"');
        $this->setHeader('Cache-Control', 'max-age=0');
        $this->setHeader('Pragma', 'public');
    }
}