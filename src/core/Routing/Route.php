<?php

namespace Core\Routing;

use Core\GlobalsContainer;

class Route
{
  protected string $method;
  protected string $path;
  protected $handler;
  protected array $parameters = [];
  protected ?string $name = null;

  public function __construct(string $method, string $path, $handler)
  {
    $this->method = $method;
    $this->path = $path;
    $this->handler = $handler;
  }

  public function method(): string
  {
    return $this->method;
  }

  public function path(): string
  {
    return $this->path;
  }

  public function matches(string $method, string $path): bool
  {
    if ($this->method === $method && $this->normalisePath($this->path) === $this->normalisePath($path)) {
      return true;
    }

    $parameter_names = [];
    $pattern = $this->normalisePath($this->path);

    $pattern = preg_replace_callback(
      '#{([^}]+)}/#',
      function (array $found) use (&$parameter_names) {
        array_push($parameter_names, rtrim($found[1], '?'));

        if (str_ends_with($found[1], '?')) {
          return '([^/]*)(?:/?)';
        }

        return '([^/]+)/';
      },
      $pattern
    );

    if (!str_contains($pattern, '+') && !str_contains($pattern, '*')) {
      return false;
    }

    preg_match_all("#{$pattern}#", $this->normalisePath($path), $matches);

    $parameter_values = [];

    if (count($matches[1]) > 0) {
      foreach ($matches[1] as $value) {
        array_push($parameter_values, $value);
      }

      $empty_values = array_fill(0, count($parameter_names), null);

      $parameter_values += $empty_values;

      $this->parameters = array_combine($parameter_names, $parameter_values);

      return true;
    }

    return false;
  }

  private function normalisePath(string $path): string
  {
    $path = trim($path, '/');
    $path = "/{$path}/";
    $path = preg_replace('/[\/]{2,}/', '/', $path);

    return $path;
  }

  public function dispatch(GlobalsContainer $globals_container)
  {
    if (is_array($this->handler)) {
      [$class, $method] = $this->handler;

      if (is_string($class)) {
        $controller = new $class($globals_container);
        return $controller->{$method}();
      }

      return $class->{$method}();
    }

    return call_user_func($this->handler);
  }

  public function parameters(): array
  {
    return $this->parameters;
  }

  public function name(string $name = null): mixed
  {
    if ($name) {
      $this->name = $name;
      return $this;
    }

    return $this->name;
  }
}