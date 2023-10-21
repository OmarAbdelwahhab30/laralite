<?php

use Laralite\Framework\Http\Kernel;
use Laralite\Framework\Http\Request;
use Laralite\Framework\Router\Router;

require_once dirname(__DIR__).DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."framework".DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."constants.php";
$request = Request::createFromGlobals();
$router = new Router();
$kernel = new Kernel($router);
$response = $kernel->handle($request);
