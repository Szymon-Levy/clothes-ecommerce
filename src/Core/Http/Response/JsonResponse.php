<?php

namespace Core\Http\Response;

class JsonResponse extends AbstractResponse
{
    public function __construct(
        protected array $data,
        protected int $statusCode = 200,
        protected array $headers = []
    )
    {
        $content = json_encode($data);

        parent::__construct($content, $statusCode, $headers);

        $this->setHeader('Content-Type', 'application/json');
    }
}