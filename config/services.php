<?php

use Laralite\Framework\Http\Kernel;
use Laralite\Framework\Router\Router;
use Laralite\Framework\Router\RouterInterface;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Container;

$routes = include BASE_PATH.DIRECTORY_SEPARATOR.'routes'.DIRECTORY_SEPARATOR.'web.php';

$container = new Container();

$container->delegate(new \League\Container\ReflectionContainer(true));

$container->add(RouterInterface::class,
    Router::class);

$container->extend(RouterInterface::class)
    ->addMethodCall("setRoutes",[new ArrayArgument($routes)]);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

return $container;