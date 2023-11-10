<?php

namespace Laralite\Framework\dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Exception;

class ConnectionFactory
{

    private string $databaseURL;

    public function __construct(string $databaseURL)
    {
        $this->databaseURL = $databaseURL;
    }

    /**
     * @throws Exception
     */

    public function create(): Connection
    {
        return DriverManager::getConnection(['url' => $this->databaseURL]);
    }

}