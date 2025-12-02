<?php

namespace Core\Http\Response;

abstract class AbstractResponse
{
    public function __construct(
        protected string $content = '',
        protected int $statusCode = 200,
        protected array $headers = []
    )
    {
        $this->setContent($content);
        $this->setStatusCode($statusCode);
        $this->setHeaders($headers);
    }

    public function send(): void
    {
        if (! headers_sent()) {
            http_response_code($this->statusCode);

            foreach ($this->headers as $name => $value) {
                header("$name: $value", false);
            }
        }

        echo $this->content;
    }

    protected function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    protected function setContent(string $content): void
    {
        $this->content = $content;
    }

    protected function setHeader(string $name, string $value) :void
    {
        $this->headers[$name] = $value;
    }

    protected function setHeaders(array $headers): void
    {
        $this->headers = array_merge($this->headers, $headers);
    }
}