<?php

namespace Core\Router;

class Route
{
    protected string $method;
    protected string $path;
    protected mixed $handler;
    protected array $middlewares = [];
    protected array $parameters = [];
    protected string $name = '';

    public function __construct(string $method, string $path, mixed $handler, $middlewares = [])
    {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
        $this->middlewares = $middlewares;
    }

    public function matches(string $method, string $uri): bool
    {
        if ($this->method !== $method) {
            return false;
        }

        return $this->matchesPath($uri);
    }

    public function matchesPath(string $uri): bool
    {
        if ($this->path === $uri) {
            return true;
        }

        $pattern = preg_replace_callback(
            '#\{([a-zA-Z0-9_]+)\}#',
            function ($matches) {
                return '(?P<' . $matches[1] . '>[^/]+)';
            },
            $this->path
        );

        $regex = "#^" . $pattern . "$#";

        if (preg_match($regex, $uri, $matches)) {
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $this->parameters[$key] = $value;
                }
            }
            return true;
        }

        return false;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function middlewares(): array
    {
        return $this->middlewares;
    }

    public function handler()
    {
        return $this->handler;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    public function name(?string $name = null)
    {
        if (is_null($name)) {
            return $this->name;
        }
        
        $this->name = $name;
    }
}