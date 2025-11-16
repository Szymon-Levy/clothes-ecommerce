<?php

namespace Core\Http;

class Request
{
    protected string $uri;
    protected string $method;
    protected array $getParams = [];
    protected array $postParams = [];
    protected array $files = [];

    public function __construct()
    {
        // Method
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // Uri
        $this->uri = $this->getUri();

        // Get params
        $this->getParams = $_GET;

        // Post params
        $this->postParams = $_POST;

        // Files
        $this->files = $_FILES;
    }

    public function get(string $name, mixed $default = null)
    {
        return $this->getParams[$name] ?? $default;
    }

    public function post(string $name, mixed $default = null)
    {
        return $this->postParams[$name] ?? $default;
    }

    public function file(string $name)
    {
        return $this->files[$name] ?? null;
    }

    public function method()
    {
        return $this->method;
    }

    public function uri()
    {
        return $this->uri;
    }

    protected function getUri()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }

        return $uri ?: '/';
    }
}