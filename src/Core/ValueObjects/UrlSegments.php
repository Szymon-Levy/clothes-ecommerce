<?php

namespace Core\ValueObjects;

class UrlSegments
{
    public function __construct(
        protected array $segments
    ){}

    public static function fromUri(string $uri): self
    {
        $normalizedUri = trim($uri, '/');

        $segments = ($normalizedUri === '') ? [] : explode('/', $normalizedUri);

        return new self($segments);
    }

    public function get(): array
    {
        return $this->segments;
    }

    public function cutLast(): self
    {
        $segments = $this->segments;

        array_pop($segments);

        return new self($segments);
    }
}