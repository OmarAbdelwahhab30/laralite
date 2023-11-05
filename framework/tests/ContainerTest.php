<?php

namespace Laralite\Framework\Tests;

use Laralite\Framework\Container\Container;
use Laralite\Framework\Container\ContainerException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class ContainerTest extends TestCase
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ContainerException
     */
    public function test_if_service_can_be_retrieved_from_container()
    {
        $con = new Container();

        $con->add("dependant-class", DependantClass::class);

        $this->assertInstanceOf(DependantClass::class, $con->get('dependant-class'));
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function test_service_exceptions()
    {
        $container = new Container();

        $this->expectException(ContainerException::class);

        $container->add('foobar');
    }

    public function test_that_a_service_exists_in_container()
    {
        $container = new Container();

        $container->add("dependant-class", DependantClass::class);

        $this->assertTrue($container->has("dependant-class"));
        $this->assertFalse($container->has("non-existing-class"));
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function test_dependency_of_other_dependencies(){
        $container =  new Container();

        //$container->add("dependent-service",DependantClass::class);

        $object = $container->get(DependantClass::class);

        $dependencyClass = $object->getDependency();

        $this->assertInstanceOf(DependencyClass::class,$dependencyClass);

        $this->assertInstanceOf(SubDependencyClass::class,$dependencyClass->getDependency());
    }
}