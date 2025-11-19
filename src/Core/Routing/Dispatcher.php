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

        // todo middleware handling

        $this->resolveController($handler);
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
}