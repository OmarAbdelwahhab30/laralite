<?php

namespace Laralite\Framework\Console;
use Laralite\Framework\Console\Command\CommandInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class Kernel
{

    public function __construct(private ContainerInterface $container,private Application $application)
    {

    }


    /**
     * @throws ContainerExceptionInterface
     * @throws \ReflectionException
     * @throws NotFoundExceptionInterface
     */
    public function handle():int
    {
        $this->registerCommands();

        return $this->application->run();

    }

    /**
     * @throws ContainerExceptionInterface
     * @throws \ReflectionException
     * @throws NotFoundExceptionInterface
     */
    private function registerCommands():void
    {

        $commandsFiles = new \DirectoryIterator(__DIR__ . DIRECTORY_SEPARATOR .'Command');

        $namespace = $this->container->get("commands-base-namespace");

        foreach ($commandsFiles as $commandsFile){

            if (!$commandsFile->isFile()){
                continue;
            }

            $Filename = $namespace.pathinfo($commandsFile,PATHINFO_FILENAME);

            if (is_subclass_of($Filename,CommandInterface::class)){

                $command = (new \ReflectionClass($Filename))->getProperty('name')->getDefaultValue();

                $this->container->add($command,$Filename);

            }

        }
    }
}