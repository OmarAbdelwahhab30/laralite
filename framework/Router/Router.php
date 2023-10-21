<?php

namespace Laralite\Framework\Router;

use Laralite\Framework\Http\Request;

class Router implements RouterInterface
{

    public function dispatch(Request $request): array
    {
        $routes = include ROUTES_PATH."web.php";
        $dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) use($routes){
            foreach ($routes as $route){
                $r->addRoute(...$route);
            }
        });
        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getUrl(),
        );
        [$status,[$controller,$method],$vars] = $routeInfo;
        return [[new $controller, $method], $vars];
    }
}
