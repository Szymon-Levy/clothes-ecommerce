<?php

namespace Core\Routing;

use Core\Container\Container;

class Dispatcher
{
    public function __construct(
        protected Container $container
    ){}

    public function dispatch(Route $route)
    {
        $handler = $route->handler();
        $middlewares = $route->middlewares();

        $controller = fn($request) => $this->resolveController($handler, $request);

        return $this->runMiddleware($middlewares, 0, $request = null, $controller);
    }

    public function dispatchHandler(array $handler, ?\Throwable $e = null)
    {
        $this->resolveController($handler, $e);
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

    protected function runMiddleware(array $middlewares, int $index, $request, callable $controller)
    {
        if ($index === count($middlewares)) {
            return $controller($request);
        }

        $middlewareClass = $middlewares[$index];

        $middleware = $this->container->get($middlewareClass);

        $next = function ($request) use ($middlewares, $index, $controller) {
            return $this->runMiddleware($middlewares, $index + 1, $request, $controller);
        };

        return $middleware($request, $next);
    }
}