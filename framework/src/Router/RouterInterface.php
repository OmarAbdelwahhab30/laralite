<?php

namespace Laralite\Framework\Router;
use Laralite\Framework\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request);
}