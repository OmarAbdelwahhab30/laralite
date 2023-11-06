<?php

namespace Laralite\Framework\Router;
use Laralite\Framework\Http\Request;
use Psr\Container\ContainerInterface;

interface RouterInterface
{
    public function dispatch(Request $request,ContainerInterface $container);

    public function setRoutes(array $routes);

}