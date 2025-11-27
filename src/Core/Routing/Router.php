<?php

namespace Core\Routing;

use Core\Http\Request;
use Core\Routing\Exceptions\MethodNotAllowedException;
use Core\Routing\Exceptions\RouteNotFoundException;
use Core\TemplateEngine\TemplateEngine;

class Router
{
    protected array $routes = [];
    protected array $urlParts;
    protected array $groupMiddlewareStack = [];
    protected string $groupPrefix = '';
    protected array $nextMiddleware = [];

    public function __construct(
        protected Request $request,
        protected TemplateEngine $templateEngine
    ){}

    public function get(string $path, callable|array $handler): Route
    {
        return $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): Route
    {
        return $this->addRoute('POST', $path, $handler);
    }

    public function group(string $pathPrefix, callable $handler): void
    {
        $previousPrefix = $this->groupPrefix;
        $previousGroupMiddleware = $this->groupMiddlewareStack;

        $this->groupPrefix .= $pathPrefix;
        
        if (!empty($this->nextMiddleware)) {
            $this->groupMiddlewareStack = array_merge($this->groupMiddlewareStack, $this->nextMiddleware);
            $this->nextMiddleware = [];
        }

        $handler($this);

        $this->groupPrefix = $previousPrefix;
        $this->groupMiddlewareStack = $previousGroupMiddleware;
    }

    public function middleware(array|string $middleware): self
    {
        $middleware = (array) $middleware;
        $this->nextMiddleware = array_merge($this->nextMiddleware, $middleware);

        return $this;
    }

    public function dispatch()
    {
        $uri = $this->request->uri();
        $method = $this->request->method();

        $matching = $this->match($method, $uri);

        if ($matching) {
            echo '<pre>';
            print_r($matching); die;
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
        $path = '/' . ltrim($path, '/');

        header(
            "Location: " . $path,
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

    protected function addRoute(string $method, string $path, $handler): Route
    {
        $fullPath = $this->groupPrefix . $path;

        $finalMiddleware = array_merge($this->groupMiddlewareStack, $this->nextMiddleware);

        $route = new Route($method, $fullPath, $handler, $finalMiddleware);
        $this->routes[] = $route;

        $this->nextMiddleware = [];

        return $route;
    }
}