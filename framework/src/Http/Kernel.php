<?php

namespace Laralite\Framework\Http;

use Doctrine\DBAL\Connection;
use Laralite\Framework\Router\RouterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Kernel
{

    public RouterInterface $router;
    public ContainerInterface $container;
    private $appEnv;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(RouterInterface $router, ContainerInterface $container)
    {


        $this->router = $router;
        $this->container = $container;
        $this->appEnv = $this->container->get("APP_ENV");
    }

    public function handle(Request $request)
    {

        try {
            dd($this->container->get(Connection::class));
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);
            $response = call_user_func_array($routeHandler, $vars);
        } catch (\Exception $exception) {
            $response = $this->createResponseException($exception);
        }
        return $response;
    }

    /**
     * @throws \Exception
     */
    public function createResponseException(\Exception $exception): Response
    {
        if (in_array($this->appEnv, ["dev", "test"])) {
            throw $exception;
        }
        if ($exception instanceof HttpException) {
            return new Response($exception->getMessage(), $exception->getStatusCode());
        }
        return new Response("Internal Server Error", Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}