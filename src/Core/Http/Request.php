<?php

namespace Core\Http;

class Request
{
    protected string $uri;
    protected string $method;
    protected array $getParams = [];
    protected array $postParams = [];
    protected array $files = [];
    protected array $routeParams = [];
    protected array $attributes = [];

    public function __construct()
    {
        // Method
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // Uri
        $this->uri = '/' . trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        // Get params
        $this->getParams = $_GET;

        // Post params
        $this->postParams = $_POST;

        // Files
        $this->files = $_FILES;
    }

    public function get(string $name, mixed $default = '', bool $lowercase = true)
    {
        if (isset($this->getParams[$name])) {
            $param = trim($this->getParams[$name]);

            if ($lowercase) {
                return strtolower($param);
            }
            
            return $param;
        }

        return $default;
    }

    public function post(string $name, mixed $default = '')
    {
        return $this->postParams[$name] ?? $default;
    }

    public function file(string $name)
    {
        return $this->files[$name] ?? null;
    }

    public function routeParam(string $name)
    {
        return $this->routeParams[$name] ?? null;
    }

    public function setRouteParams(array $params)
    {
        $this->routeParams = $params;
    }

    public function method()
    {
        return $this->method;
    }

    public function uri()
    {
        return $this->uri;
    }

    public function setAttribute(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function getAttribute(string $name, mixed $default = '')
    {
        return $this->attributes[$name] ?? $default;
    }
}