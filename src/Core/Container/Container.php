<?php

namespace Core\Container;

use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;

class Container
{
    protected array $bindings = [];
    protected array $instances = [];

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
            $this->instances[$name] = $instance;
    
            return $instance;
        }

        if (! class_exists($name) && ! interface_exists($name)) {
            throw new Exception("Unknown class or interface: {$name}");
        }

        $classReflection = new ReflectionClass($name);

        if (! $classReflection->isInstantiable()) {
            throw new Exception("Class {$name} is not instanciable");
        }

        $constructor = $classReflection->getConstructor();

        if ($constructor === null) {
            $instance = $classReflection->newInstance();
            $this->instances[$name] = $instance;
    
            return $instance;
        }

        $parentClass = $classReflection->getParentClass();

        if ($parentClass && $parentClass->getConstructor()) {
            $params = array_merge(
                $constructor->getParameters(),
                $parentClass->getConstructor()->getParameters()
            );
        } else {
            $params = $constructor->getParameters();
        } 

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
                $dependencyClassName = $type->getName();

                if ($dependencyClassName === self::class) {
                    $dependencies[] = $this;
                } else {
                    $dependencies[] = $this->get($dependencyClassName);
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

        $instance = $classReflection->newInstanceArgs($dependencies);

        $parentClass = $classReflection->getParentClass();

        if ($parentClass && $parentClass->getConstructor()) {
            $parentConstructor = $parentClass->getConstructor();
            $parentParams = $parentConstructor->getParameters();
            $parentDependencies = [];

            foreach ($parentParams as $param) {
                $type = $param->getType();

                if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                    $dependencyClassName = $type->getName();
                    if ($dependencyClassName === self::class) {
                        $parentDependencies[] = $this;
                    } else {
                        $parentDependencies[] = $this->get($dependencyClassName);
                    }
                } elseif ($param->isDefaultValueAvailable()) {
                    $parentDependencies[] = $param->getDefaultValue();
                } else {
                    throw new Exception("Cannot autowire parent dependency \${$param->getName()} in {$parentClass->getName()}");
                }
            }

            $parentConstructor->invokeArgs($instance, $parentDependencies);
        }

        $this->instances[$name] = $instance;

        return $instance;
    }

    public function call(object $instance, string $methodName, array $extraParams = [])
    {
        $methodReflection = new ReflectionMethod($instance, $methodName);
        $params = $methodReflection->getParameters();
        $dependencies = [];

        foreach ($params as $param) {
            $name = $param->getName();
            $type = $param->getType();

            if (array_key_exists($name, $extraParams)) {
                $dependencies[] = $extraParams[$name];
                continue; 
            }

            if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                $dependencyClassName = $type->getName();
                
                if ($dependencyClassName === self::class) {
                    $dependencies[] = $this;
                } else {
                    $dependencies[] = $this->get($dependencyClassName);
                }
                
                continue;
            } 
                        
            if ($param->isDefaultValueAvailable()) {
                $dependencies[] = $param->getDefaultValue();
                continue;
            }

            throw new Exception("Cannot resolve required parameter \${$name} for method {$methodName} in " . get_class($instance));
        }
        
        return $methodReflection->invokeArgs($instance, $dependencies);
    }
}