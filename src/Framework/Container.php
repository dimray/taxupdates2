<?php

declare(strict_types=1);

namespace Framework;

use ReflectionClass;
use ReflectionNamedType;
use Closure;
use Exception;

class Container
{
    private array $registry = [];
    private array $instances = [];
    // check if there is already an instance running, so don't get multiple database instances when entering data into more than one table

    public function set(string $name, Closure $value): void
    {
        $this->registry[$name] = $value;
    }

    public function get(string $class_name): object
    {
        // Return the same instance if already resolved
        if (array_key_exists($class_name, $this->instances)) {
            return $this->instances[$class_name];
        }

        // Saves the instance if in Registry
        if (array_key_exists($class_name, $this->registry)) {

            $this->instances[$class_name] = $this->registry[$class_name]();

            return $this->instances[$class_name];
        }

        $reflector = new ReflectionClass($class_name);

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {

            $this->instances[$class_name] = new $class_name; // <-- FIX: Cache this instance
            return $this->instances[$class_name];
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $parameter) {

            $type = $parameter->getType();

            if ($type === null) {
                throw new Exception("Constructor parameter {$parameter->getName()} in the $class_name class has no type declaration so cannot be resolved by the Container");
            }

            if (!($type instanceof ReflectionNamedType)) {
                throw new Exception("Constructor parameter {$parameter->getName()} in the $class_name class is an invalid type: $type - only single named types are supported");
            }

            if ($type->isBuiltin()) {
                throw new Exception("Unable to resolve constructor parameter {$parameter->getName()} of type $type in the $class_name class (add class to the Service Container)");
            }

            $name = $type->getName();

            $dependencies[] = $this->get($name);
        }

        $this->instances[$class_name] = new $class_name(...$dependencies); // <-- FIX: Cache this instance
        return $this->instances[$class_name];
    }
}
