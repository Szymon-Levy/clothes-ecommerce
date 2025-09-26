<?php

namespace Core;

class GlobalsContainer 
{
  protected array $globals;

  public function set(string $name, mixed $variable): void
  {
    $this->globals[$name] = $variable;
  }

  public function get(string $name): mixed
  {
    return $this->globals[$name] ?? null;
  }
}