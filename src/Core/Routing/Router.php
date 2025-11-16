<?php

namespace Core\Routing;

use Core\Config\Config;
use Core\Container\Container;
use Core\Http\Request;
use Core\TemplateEngine\TemplateEngine;

class Router
{
    protected array $routes = [];
    protected array $errorHandlers = [];
    protected array $urlParts;
    protected string $groupPathPrefix = '';
    protected array $groupMiddlewares = [];

    public function __construct(
        protected Container $container,
        protected Request $request,
        protected TemplateEngine $templateEngine,
        protected Config $config
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
            $this->passUrpPartsToTwig($uri);

            $this->request->setRouteParams($matching->parameters());

            try {
                return $this->resolveController($matching->handler());
            } catch (\Throwable $e) {
                return $this->dispatchError($e);
            }
        }

        $paths = $this->paths();

        if (in_array($uri, $paths)) {
            return $this->dispatchNotAllowed();
        }

        return $this->dispatchNotFound();
    }

    public function errorHandler(int $code, array $handler)
    {
        $this->errorHandlers[$code] = $handler;
    }

    public function redirect($path)
    {
        header(
            "Location: " . $this->config->system()['doc_root'] . $path,
            $replace = true,
            $code = 301
        );

        exit;
    }

    public function urlParts(): array
    {
        return $this->urlParts;
    }

    public function passUrpPartsToTwig(string $uri)
    {
        $this->urlParts = explode('/', trim($uri, '/'));

        $this->templateEngine->addGlobalVariable('url_parts', $this->urlParts());
    }

    protected function resolveController($handler, $param = null)
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;

            if (is_string($class)) {
                $controller = $this->container->get($class);

                return $controller->{$method}($param);
            }

            return $class->{$method}($param);
        }

        return call_user_func($handler, $param);
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

    protected function dispatchNotAllowed()
    {
        http_response_code(400);

        $this->errorHandlers[400] ??= function() {echo '400 Bad Request';};

        $this->resolveController($this->errorHandlers[400]);
    }

    protected function dispatchNotFound()
    {
        http_response_code(404);

        $this->errorHandlers[404] ??= function() {echo '404 Not Found';};

        $this->resolveController($this->errorHandlers[404]);
    }

    public function dispatchError(\Throwable $e)
    {
        http_response_code(500);
        error_log($e);

        $this->errorHandlers[500] ??= function() {echo '500 Internal Server Error';};

        $this->resolveController($this->errorHandlers[500], $e);
    }
}