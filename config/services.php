<?php

use Laralite\Framework\Controllers\AbstractController;
use Laralite\Framework\Http\Kernel;
use Laralite\Framework\Router\Router;
use Laralite\Framework\Router\RouterInterface;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$routes = include BASE_PATH . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'web.php';

$dotEnv = new Dotenv();

$dotEnv->load(dirname(__DIR__) . DIRECTORY_SEPARATOR . ".env");

$APP_ENV = $_SERVER['APP_ENV'];

$templatesPath = BASE_PATH . DIRECTORY_SEPARATOR . 'templates';

$container = new Container();

$container->delegate(new ReflectionContainer(true));

$container->add("APP_ENV", new StringArgument($APP_ENV));

$container->add(RouterInterface::class,
    Router::class);

$container->extend(RouterInterface::class)
    ->addMethodCall("setRoutes", [new ArrayArgument($routes)]);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

$container->addShared('filesystem-loader', FilesystemLoader::class)
    ->addArgument(new StringArgument($templatesPath));

$container->addShared(Environment::class)
    ->addArgument('filesystem-loader');

$container->add(AbstractController::class);

$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

return $container;