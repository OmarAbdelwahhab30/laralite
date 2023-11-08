<?php

namespace App\controllers;

use Laralite\Framework\Controllers\AbstractController;
use Laralite\Framework\Http\Response;

class SiteController extends AbstractController
{

    public HomeController $obj;

    public function __construct(HomeController $obj)
    {
        $this->obj = $obj;
    }
    public function index()
    {
        return $this->render("first.html.twig");
    }

}