<?php

use App\controllers\SiteController;

return [
    ["GET", "/", [SiteController::class,"index"]],

    ["GET",'/user/{name:.+}',function($name){
        return new \Laralite\Framework\Http\Response("My name is ".$name);
    }],
];