<?php

namespace App\controllers;

use Laralite\Framework\Controllers\AbstractController;
use Twig\Environment;

class HomeController extends AbstractController
{

    public string $name = "Home";

    public function __construct(private Environment $twig)
    {

    }

    public function index($id)
    {
        return $this->render("posts.html.twig",['postID' => $id]);
    }
}