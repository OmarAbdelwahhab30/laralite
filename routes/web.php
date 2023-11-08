<?php

use App\controllers\SiteController;

return [
    ["GET", "/", [SiteController::class,"index"]],

    ["GET",'/user/{name:.+}',function($name){
        return new \Laralite\Framework\Http\Response("My name is ".$name);
    }],

    ["GET","/post/{id}",[\App\controllers\HomeController::class,"index"]],

    ["GET","/createPost",[\App\controllers\PostsController::class,"create"]]
];