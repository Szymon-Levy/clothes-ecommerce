<?php

namespace Core\Routing;

use Exception;
use Core\GlobalsContainer;

class Router 
{
  protected array $routes = [];
  protected array $error_handlers = [];
  protected Route $current;
  protected GlobalsContainer $globals_container;

  public function __construct(GlobalsContainer $globals_container)
  {
    $this->globals_container = $globals_container;
  }

  public function add(string $method, string $path, $handler): Route
  {
    $route = $this->routes[] = new Route($method, $path, $handler);
    return $route;
  }

  public function dispatch()
  {
    $paths = $this->paths();

    $request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $uri = parse_url($uri, PHP_URL_PATH);
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

    if ($base !== '' && str_starts_with($uri, $base)) {
      $uri = substr($uri, strlen($base));
    }

    $request_path = $uri ?: '/';

    $matching = $this->match($request_method, $request_path);

    if ($matching) {
      $this->passUrpPartsToTwig($request_path);

      $this->current = $matching;

      try {
        return $matching->dispatch($this->globals_container);
      } catch (\Throwable $e) {
        $this->dispatchError($e);
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

  public function errorHandler(int $code, array $handler)
  {
    $this->error_handlers[$code] = $handler;
  }

  public function dispatchNotAllowed()
  {
    $this->error_handlers[400] ??= fn() => 'not allowed';
    return $this->error_handlers[400]();
  }

  public function dispatchNotFound()
  {
    http_response_code(404);

    [$class, $method] = $this->error_handlers[404];
    $controller = new $class($this->globals_container);

    return $controller->{$method}();
  }

  public function dispatchError(\Throwable $e)
  {
    http_response_code(500);

    echo DEV ? $e->getMessage() : 'Server error!';
  }

  public function redirect($path)
  {
    header("Location: {$path}", $replace = true, $code = 301);
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

  public function passUrpPartsToTwig(string $uri){
    $url_parts = explode('/', trim($uri, '/'));

    $this->globals_container->get('twig')->addGlobal('url_parts', $url_parts);
  }
}