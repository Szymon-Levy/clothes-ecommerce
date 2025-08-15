<?php

namespace Core\Routing;

class Router 
{
  protected array $routes = [];

  public function add(string $method, string $path, callable $handler): Route
  {
    $route = $this->routes[] = new Route($method, $path, $handler);
    return $route;
  }

  public function dispatch()
  {
    $paths = $this->paths();

    $request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $request_path = $_SERVER['REQUEST_URI'] ?? '/';

    $matching = $this->match($request_method, $request_path);

    if ($matching) {
      try {
        return $matching->dispatch();
      } catch (\Throwable $e) {
        return $this->dispatchError();
      }
    }

    if (in_array($request_path, $paths)) {
      return $this->dispatchNotAllowed();
    }

    return $this->dispatchNotFound();
  }

  private function paths(): array
  {
    $paths = [];

    foreach($this->routes as $route) {
      $paths[] = $route->path();
    }

    return $paths;
  }

  private function match(string $method, string $path): ?Route
  {
    foreach($this->routes as $route) {
      if ($route->matches($method, $path)) {
        return $route;
      }
    }

    return null;
  }
}