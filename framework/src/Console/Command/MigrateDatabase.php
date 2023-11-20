<?php

namespace Laralite\Framework\Console\Command;

class MigrateDatabase implements CommandInterface
{

    public string $name = "database:migrations:migrate";

    public function execute(array $params = []): int
    {

        return 0;
    }
}