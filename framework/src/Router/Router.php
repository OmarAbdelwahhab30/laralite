<?php

namespace Laralite\Framework\Router;

use FastRoute\Dispatcher;
use Laralite\Framework\Http\HttpException;
use Laralite\Framework\Http\HttpRequestMethodException;
use Laralite\Framework\Http\Request;
use Psr\Container\ContainerInterface;

class Router implements RouterInterface
{

    public array $routes = [];

    /**
     * @throws HttpRequestMethodException
     * @throws HttpException
     */
    public function dispatch(Request $request,ContainerInterface $container): array
    {
        $routeInfo = $this->extractRouteInfo($request);
        [$handler, $vars] = $routeInfo;
        if (is_array($handler)) {

            [$controllerID, $method] = $handler;
            $controller = $container->get($controllerID);
            $handler = [$controller, $method];
        }
        return [$handler, $vars];
    }

    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * @throws HttpRequestMethodException
     * @throws HttpException
     */
    private function extractRouteInfo(Request $request)
    {

        $dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
            foreach ($this->routes as $route) {
                $r->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getUrl(),
        );

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                $exception = new HttpRequestMethodException("The allowed methods are $allowedMethods", 400);
                $exception->setStatusCode(400);
                throw $exception;
            default:
                $exception = new HttpException('Not found');
                $exception->setStatusCode(404);
                throw $exception;
        }
    }
}
