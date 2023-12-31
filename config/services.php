<?php

use Doctrine\DBAL\Connection;
use Laralite\Framework\Console\Application;
use Laralite\Framework\Console\Command\MigrateDatabase;
use Laralite\Framework\Controllers\AbstractController;
use Laralite\Framework\dbal\ConnectionFactory;
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

$dbPath = 'sqlite:///' . BASE_PATH . DIRECTORY_SEPARATOR .'var'.DIRECTORY_SEPARATOR.'db.sqlite';



$container = new Container();

$container->add("commands-base-namespace",
    new StringArgument("Laralite\\Framework\\Console\\Command\\"),
);


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

$container->add(ConnectionFactory::class)
    ->addArguments([
        new StringArgument($dbPath)
    ]);

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

$container->add(\Laralite\Framework\Console\Kernel::class)
    ->addArguments([$container,Application::class]);

$container->add(Application::class)
    ->addArgument($container);

$container->add("migrate",
    MigrateDatabase::class)
    ->addArguments([
        Connection::class,
        BASE_PATH.DIRECTORY_SEPARATOR."migrations".DIRECTORY_SEPARATOR
    ]);
return $container;