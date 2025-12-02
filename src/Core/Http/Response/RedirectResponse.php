<?php

namespace Core\Http\Response;

class RedirectResponse extends AbstractResponse
{
    public function __construct(
        protected string $url = '/',
        protected int $statusCode = 301,
        protected array $headers = []
    )
    {
        parent::__construct('', $statusCode, $headers);

        $url = '/' . ltrim($url, '/');

        $this->setHeader('Location', $url);

        if (! isset($this->headers['Content-Type'])) {
            $this->setHeader('Content-Type', 'text/html; charset=UTF-8');
        }
    }
}