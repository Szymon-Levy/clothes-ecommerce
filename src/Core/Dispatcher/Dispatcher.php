<?php

namespace Core\Dispatcher;

use Core\Container\Container;
use Core\Http\Request\Request;
use Core\Router\Route;

class Dispatcher
{
    public function __construct(
        protected Container $container,
        protected Request $request
    ){}

    public function dispatch(Route $route)
    {
        $handler = $route->handler();
        $middlewares = $route->middlewares();

        $controller = fn() => $this->resolveController($handler);

        return $this->runMiddleware($middlewares, 0, $this->request, $controller);
    }

    public function dispatchHandler(array $handler, array $params = [])
    {
        return $this->resolveController($handler, $params);
    }

    protected function resolveController($handler, array $params = [])
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;

            if (is_string($class)) {
                $controller = $this->container->get($class);

                return $this->container->call($controller, $method, $params);
            }

            return $class->{$method}($params);
        }

        return call_user_func_array($handler, $params);
    }

    protected function runMiddleware(array $middlewares, int $index, $request, callable $controller)
    {
        if ($index === count($middlewares)) {
            return $controller();
        }

        $middlewareClass = $middlewares[$index];

        $middleware = $this->container->get($middlewareClass);

        $next = function ($request) use ($middlewares, $index, $controller) {
            return $this->runMiddleware($middlewares, $index + 1, $request, $controller);
        };

        return $middleware($request, $next);
    }
}