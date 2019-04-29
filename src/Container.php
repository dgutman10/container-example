<?php


namespace App;


class Container
{
    protected static $instance = null;
    private $bindings;

    public function bind($key, $resolver)
    {
        $this->bindings[$key] = [
            'resolver' => $resolver
        ];
    }

    public function make($key)
    {
        if (isset($this->bindings[$key])) {
           $resolver = $this->bindings[$key]['resolver'];
        } else {
            $resolver = $key;
        }

        if ($resolver instanceof \Closure)
        {
            return $resolver($this);
        }

        return $this->build($resolver);

    }

    public function build($class)
    {
        $reflectionClass = new \ReflectionClass($class);

        $constructor = $reflectionClass->getConstructor();

        if ($constructor == null) {
            return new $class;
        }

        $parameters = $constructor->getParameters();

        $arguments = [];

        foreach ($parameters as $parameter) {
            $className = $parameter->getClass()->getName();
            $arguments[] = $this->build($className);
        }

        return $reflectionClass->newInstanceArgs($arguments);
    }

    public static function getInstance()
    {
        if (static::$instance == null) {
            static::$instance = new static;
        }

        return static::$instance;
    }
}