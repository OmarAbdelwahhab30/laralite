<?php

namespace App\controllers;

use Laralite\Framework\Controllers\AbstractController;

class PostsController extends AbstractController
{

    public function create()
    {
        return $this->render("create-post.html.twig");
    }
}