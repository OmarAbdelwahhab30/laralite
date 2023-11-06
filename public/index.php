<?php

use Laralite\Framework\Http\Kernel;
use Laralite\Framework\Http\Request;


define("BASE_PATH",dirname(__DIR__));

require_once BASE_PATH.DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";

require_once BASE_PATH . DIRECTORY_SEPARATOR . "framework" . DIRECTORY_SEPARATOR ."src".DIRECTORY_SEPARATOR.
    "config" .DIRECTORY_SEPARATOR."constants.php";

$container = require BASE_PATH.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."services.php";

$request = Request::createFromGlobals();

$response = $container->get(Kernel::class)->handle($request);

$response->send();
