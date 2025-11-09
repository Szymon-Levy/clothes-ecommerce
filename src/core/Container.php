<?php

namespace Core;

use Exception;
use ReflectionClass;
use ReflectionNamedType;

class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function set(string $name, callable $resolver):void
    {
        $this->bindings[$name] = $resolver;
    }

    public function get(string $name)
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        if (isset($this->bindings[$name])) {
            $instance = $this->bindings[$name]($this);
            $this->instances[] = $instance;
    
            return $instance;
        }

        if (! class_exists($name) && ! interface_exists($name)) {
            throw new Exception("Unknown class or interface: {$name}");
        }

        $class_reflection = new ReflectionClass($name);

        if (! $class_reflection->isInstantiable()) {
            throw new Exception("Class {$name} is not instanciable");
        }

        $constructor = $class_reflection->getConstructor();

        if ($constructor === null) {
            $instance = $class_reflection->newInstance();
            $this->instances[] = $instance;
    
            return $instance;
        }

        $params = $constructor->getParameters();
        $dependencies = [];

        foreach ($params as $param) {
            $type = $param->getType();

            if ($type === null) {
                if ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();

                    continue;
                }

                throw new Exception("Unknown parameter type \${$param->getName()} in {$name}");
            }

            if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                $dependency_class_name = $type->getName();

                if ($dependency_class_name === self::class) {
                    $dependencies[] = $this;
                } else {
                    $dependencies[] = $this->get($dependency_class_name);
                }

                continue;
            }

            if ($type->isBuiltin()) {
                if ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();

                    continue;
                }

                throw new Exception("Parameter \${$param->getName()} is builtin type and hasn't got default value in {$name}");
            }

            throw new Exception("Unsupported parameter type \${$param->getName()} in {$name}");
        }

        $instance = $class_reflection->newInstanceArgs($dependencies);
        $this->instances[$name] = $instance;

        return $instance;
    }
}