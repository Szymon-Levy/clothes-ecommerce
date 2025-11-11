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

    public function add(string $method, string $path, $handler): Route
    {
        $route = $this->routes[] = new Route($method, $path, $handler);
        return $route;
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
                return $matching->dispatch($this->container);
            } catch (\Throwable $e) {
                $this->dispatchError($e);
            }
        }

        if (in_array($requestPath, $paths)) {
            return $this->dispatchNotAllowed();
        }

        return $this->dispatchNotFound();
    }

    private function paths(): array
    {
        $paths = [];

        foreach ($this->routes as $route) {
            $paths[] = $route->path();
        }

        return $paths;
    }

    private function match(string $method, string $path): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->matches($method, $path)) {
                return $route;
            }
        }

        return null;
    }

    public function errorHandler(int $code, array $handler)
    {
        $this->errorHandlers[$code] = $handler;
    }

    public function dispatchNotAllowed()
    {
        $this->errorHandlers[400] ??= fn() => 'not allowed';
        return $this->errorHandlers[400]();
    }

    public function dispatchNotFound()
    {
        http_response_code(404);

        [$class, $method] = $this->errorHandlers[404];
        
        $controller = $this->container->get($class);

        return $controller->{$method}();
    }

    public function dispatchError(\Throwable $e)
    {
        http_response_code(500);
        error_log($e);

        if ($this->config->system('dev')) {
            echo "<h1>Application error</h1>";
            echo "<p><strong>Message:</strong> {$e->getMessage()}</p>";
            echo "<p><strong>File:</strong> {$e->getFile()}</p>";
            echo "<p><strong>Line:</strong> {$e->getLine()}</p>";
            echo "<p><strong>Type:</strong> " . get_class($e) . "</p>";
            echo "<pre>{$e->getTraceAsString()}</pre>";
        } else {
            echo "Server error!";
        }
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
}