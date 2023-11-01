<?php

namespace Laralite\Framework\Router;

use FastRoute\Dispatcher;
use Laralite\Framework\Http\HttpException;
use Laralite\Framework\Http\HttpRequestMethodException;
use Laralite\Framework\Http\Request;

class Router implements RouterInterface
{

    public function dispatch(Request $request)
    {

        $routeInfo = $this->extractRouteInfo($request);
        [$handler, $vars] = $routeInfo;
        if (is_array($handler)) {
            [$controller, $method] = $handler;
            $handler = [new $controller, $method];
        }
        return [$handler, $vars];
    }

    private function extractRouteInfo(Request $request)
    {
        $routes = include ROUTES_PATH . "web.php";
        $dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
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
