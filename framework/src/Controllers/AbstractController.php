<?php
namespace Laralite\Framework\Controllers;

use Laralite\Framework\Http\Response;
use Psr\Container\ContainerInterface;
use Twig\Environment;

abstract class AbstractController
{

    protected ?ContainerInterface $container = null;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function render($template,array $params = [],Response $response = null): Response{
        $content = $this->container->get(Environment::class)->render($template,$params);

        $response ??= new Response();

        $response->setContent($content);

        return $response;

    }
}