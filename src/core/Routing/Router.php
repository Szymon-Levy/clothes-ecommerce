<?php

namespace Core\Routing;

use Core\Config\Config;
use Core\Container\Container;
use Core\TemplateEngine\TemplateEngine;
use Exception;

class Router
{
    protected array $routes = [];
    protected array $errorHandlers = [];
    protected Route $current;
    protected array $urlParts;

    public function __construct(
        protected Container $container,
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

    public function dispatch()
    {
        $paths = $this->paths();

        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }

        $requestPath = $uri ?: '/';

        $matching = $this->match($requestMethod, $requestPath);

        if ($matching) {
            $this->passUrpPartsToTwig($requestPath);

            $this->current = $matching;

            try {
                return $this->resolveController($matching->handler());
            } catch (\Throwable $e) {
                $this->dispatchError($e);
            }
        }

        if (in_array($requestPath, $paths)) {
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

    public function current(): ?Route
    {
        return $this->current;
    }

    public function route(string $name, array $parameters): string
    {
        foreach ($this->routes as $route) {
            if ($route->name() === $name) {
                $finds = [];
                $replaces = [];

                foreach ($parameters as $key => $value) {
                    array_push($finds, "{{$key}}");
                    array_push($replaces, $value);

                    array_push($finds, "{{$key}?}");
                    array_push($replaces, $value);
                }

                $path = $route->path();
                $path = str_replace($finds, $replaces, $path);
                $path = preg_replace('#{[^}]+}#', '', $path);

                return $path;
            }
        }

        throw new Exception('no route with that name');
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
        $route = $this->routes[] = new Route($method, $path, $handler);
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

    protected function dispatchError(\Throwable $e)
    {
        http_response_code(500);
        error_log($e);

        $this->errorHandlers[500] ??= function() {echo '500 Internal Server Error';};

        $this->resolveController($this->errorHandlers[500], $e);
    }
}