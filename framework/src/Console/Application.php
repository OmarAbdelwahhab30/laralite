<?php

namespace Laralite\Framework\Console;

use Psr\Container\ContainerInterface;

class Application
{

    public function __construct(private ContainerInterface $container)
    {

    }

    /**
     * @throws ConsoleException
     */
    public function run(): int
    {
        $argv = $_SERVER['argv'];
        $commandName = $argv[1] ?? null;


        if (!$commandName) {
            throw new ConsoleException('A command name must be provided');
        }


        $command = $this->container->get($commandName);


        $args = array_slice($argv, 2);

        $options = $this->parseOptions($args);


        return $command->execute($options);
    }

    private function parseOptions(array $args): array
    {
        $options = [];

        foreach ($args as $arg) {
            if (str_starts_with($arg, '--')) {

                $option = explode('=', substr($arg, 2));
                $options[$option[0]] = $option[1] ?? true;

            }
        }

        return $options;
    }
}
