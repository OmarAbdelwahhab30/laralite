<?php

namespace Laralite\Framework\Console\Command;

interface CommandInterface
{

    public function execute(array $params = []):int ;
}