<?php

namespace Laralite\Framework\Container;

use Psr\Container\ContainerInterface;
use ReflectionException;

class Container implements ContainerInterface
{
    private array $services = [];

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function add(string $id, string|object $concrete = null)
    {
        if (null === $concrete) {
            if (!class_exists($id)) {
                throw new ContainerException("Service $id doesn't exist.");
            }
            $concrete = $id;
        }
        $this->services[$id] = $concrete;
        $this->resolve($this->services[$id]);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function get(string $id)
    {
        if (!$this->has($id)) {
            if (!class_exists($id)) {
                throw new ContainerException("Service $id cannot be resolved");
            }
            $this->add($id);
        }
        return $this->resolve($this->services[$id]);
    }

    /**
     * @throws ReflectionException|ContainerException
     */
    private function resolve($class)
    {
        $reflection = new \ReflectionClass($class);

        $constructor = $reflection->getConstructor();

        if (null === $constructor) {

            return $reflection->newInstance();

        }

        $constructorArgs = $constructor->getParameters();

        $dependencies = $this->resolveConstructorArgs($constructorArgs);

        return $reflection->newInstanceArgs($dependencies);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    private function resolveConstructorArgs(array $constructorArgs): array
    {
        $dependencies = [];

        foreach ($constructorArgs as $arg) {

            $classType = $arg->getType();

            $className = $classType->getName();

            $dependency = $this->get($className);

            $dependencies[] = $dependency;

        }

        return $dependencies;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }
}