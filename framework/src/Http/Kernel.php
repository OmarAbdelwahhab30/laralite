<?php

namespace Laralite\Framework\Http;

use Laralite\Framework\Router\RouterInterface;
use Psr\Container\ContainerInterface;

class Kernel
{

    public RouterInterface $router;
    public ContainerInterface $container;
    public function __construct(RouterInterface $router,ContainerInterface $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    public function handle(Request $request)
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request,$this->container);
            $response = call_user_func_array($routeHandler, $vars);


        } catch (HttpRequestMethodException $exception) {
            $response = new Response($exception->getMessage(), $exception->getStatusCode());
        } catch (HttpException $exception) {
            $response = new Response($exception->getMessage(), $exception->getStatusCode());
        } catch (\Exception $exception) {
            $response = new Response($exception->getMessage(), 500);
        }
        return $response;
    }
}