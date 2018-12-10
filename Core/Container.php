<?php

namespace Core;

use ReflectionClass;
use ReflectionMethod;
use Exception;

class Container
{
    protected static $instances = [];

    public static function make($className)
    {
        if (array_key_exists($className, self::$instances)) {
            return self::$instances[$className];
        }
        $static = new static;
        self::$instances[$className] = $static->resolve($className);

        return self::$instances[$className];
    }

    public function resolve($className)
    {
        if (!class_exists($className)) {
            throw new Exception("Container: Class $className does not exists");
        }

        $reflector = new ReflectionClass($className);
        if (!$reflector->isInstantiable()) {
            throw new Exception("Container: Class $className is not instanctiable");
        }

        $contructor = $reflector->getConstructor();
        if (!$contructor) {
            return $reflector->newInstance();
        }

        $contructorParams = $contructor->getParameters();
        if (count($contructorParams) === 0) {
            return $reflector->newInstance();
        }
        $dependencies = $this->getDependencies($contructorParams);

        return $reflector->newInstanceArgs($dependencies);
    }

    public static function methodResolve($className, $methodName)
    {
        $static = new static;
        $reflection = new ReflectionMethod($className, $methodName);
        $argsMethod = $static->getDependencies($reflection->getParameters());
        $class = self::make($className);

        return call_user_func_array([$class, $methodName], $argsMethod);
    }

    public function getDependencies($params)
    {
        $dependencies = [];
        foreach ($params as $param) {
            if ($paramClass = $param->getClass()) {
                $dependencies[] = $this->resolve($paramClass->name);
            } else {
                throw new Exception('Container: Failed to resolve dependency');
            }
        }

        return $dependencies;
    }
}
