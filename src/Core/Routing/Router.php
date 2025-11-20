<?php

namespace Core\Routing;

use Core\Config\Config;
use Core\Http\Request;
use Core\Routing\Exceptions\MethodNotAllowedException;
use Core\Routing\Exceptions\RouteNotFoundException;
use Core\TemplateEngine\TemplateEngine;

class Router
{
    protected array $routes = [];
    protected array $errorHandlers = [];
    protected array $urlParts;
    protected string $groupPathPrefix = '';
    protected array $groupMiddlewares = [];

    public function __construct(
        protected Request $request,
        protected TemplateEngine $templateEngine,
        protected Config $config,
        protected Dispatcher $dispatcher
    ){}

    public function get(string $path, $handler)
    {
        return $this->add('GET', $path, $handler);
    }

    public function post(string $path, $handler)
    {
        return $this->add('POST', $path, $handler);
    }

    public function group(string $pathPrefix, callable $handler)
    {
        $this->groupPathPrefix = $pathPrefix;

        $handler($this);

        $this->groupPathPrefix = '';
        $this->groupMiddlewares = [];
    }

    public function middleware(array|string $names)
    {
        if (is_string($names)) {
            $names = (Array) $names;
        }

        $this->groupMiddlewares = $names;

        return $this;
    }

    public function dispatch()
    {
        $uri = $this->request->uri();
        $method = $this->request->method();

        $matching = $this->match($method, $uri);

        if ($matching) {
            $this->passUrlPartsToTwig($uri);

            $this->request->setRouteParams($matching->parameters());

            return $matching;
        }

        $paths = $this->paths();

        if (in_array($uri, $paths)) {
            throw new MethodNotAllowedException("Method {$method} not allowed on {$uri}");
        }

        throw new RouteNotFoundException("Route {$uri} not found");
    }

    public function redirect($path)
    {
        header(
            "Location: " . $this->config->system('doc_root') . trim($path, '/'),
            $replace = true,
            $code = 301
        );

        exit;
    }

    public function urlParts(): array
    {
        return $this->urlParts;
    }

    public function passUrlPartsToTwig(string $uri)
    {
        $this->urlParts = explode('/', trim($uri, '/'));

        $this->templateEngine->addGlobalVariable('url_parts', $this->urlParts());
    }

    protected function paths(): array
    {
        $paths = [];

        foreach ($this->routes as $route) {
            $paths[] = $route->path();
        }

        return $paths;
    }

    protected function match(string $method, string $path): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->matches($method, $path)) {
                return $route;
            }
        }

        return null;
    }

    protected function add(string $method, string $path, $handler): Route
    {
        $path = $this->groupPathPrefix . $path;

        $route = $this->routes[] = new Route($method, $path, $handler, $this->groupMiddlewares);

        if ($this->groupPathPrefix === '') {
            $this->groupMiddlewares = [];
        }

        return $route;
    }
}