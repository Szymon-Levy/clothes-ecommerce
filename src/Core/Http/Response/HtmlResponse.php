<?php

namespace Core\Http\Response;

class HtmlResponse extends AbstractResponse
{
    public function __construct(
        protected string $content,
        protected int $statusCode = 200,
        protected array $headers = []
    )
    {
        parent::__construct($content, $statusCode, $headers);

        if (! isset($this->headers['Content-Type'])) {
            $this->setHeader('Content-Type', 'text/html; charset=UTF-8');
        }
    }
}