<?php

namespace Laralite\Framework\Http;

use Laralite\Framework\Router\RouterInterface;

class Kernel
{

    public RouterInterface $router;
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function handle(Request $request)
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request);
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