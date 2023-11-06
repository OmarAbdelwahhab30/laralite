<?php

namespace App\controllers;

use Laralite\Framework\Http\Response;

class SiteController
{

    public HomeController $obj;

    public function __construct(HomeController $obj)
    {
        $this->obj = $obj;
    }
    public function index()
    {
        $content = "Hello {$this->obj->name}";

        return new Response($content);
    }

}