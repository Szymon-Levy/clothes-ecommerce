<?php

namespace Core\Routing;

class Route
{
    protected string $method;
    protected string $path;
    protected mixed $handler;
    protected array $middlewares = [];
    protected array $parameters = [];

    public function __construct(string $method, string $path, mixed $handler, $middlewares = [])
    {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
        $this->middlewares = $middlewares;
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

    public function matches(string $method, string $path): bool
    {
        if ($this->method === $method && $this->normalisePath($this->path) === $this->normalisePath($path)) {
            return true;
        }

        $parameterNames = [];

        $pattern = $this->normalisePath($this->path);

        $pattern = preg_replace_callback(
            '#{([^}]+)}/#',
            function (array $found) use (&$parameterNames) {
                array_push($parameterNames, rtrim($found[1], '?'));

                if (str_ends_with($found[1], '?')) {
                    return '([^/]*)(?:/?)';
                }

                return '([^/]+)/';
            },
            $pattern
        );

        if (!str_contains($pattern, '+') && !str_contains($pattern, '*')) {
            return false;
        }

        preg_match_all("#{$pattern}#", $this->normalisePath($path), $matches);

        $parameterValues = [];

        if (count($matches[1]) > 0) {
            foreach ($matches[1] as $value) {
                array_push($parameterValues, $value);
            }

            $emptyValues = array_fill(0, count($parameterNames), null);

            $parameterValues += $emptyValues;

            $this->parameters = array_combine($parameterNames, $parameterValues);

            return true;
        }

        return false;
    }

    protected function normalisePath(string $path): string
    {
        $path = trim($path, '/');
        $path = "/{$path}/";
        $path = preg_replace('/[\/]{2,}/', '/', $path);

        return $path;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }
}