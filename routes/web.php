<?php

use App\controllers\SiteController;

return [
    ["POST", "/", [SiteController::class,"index"]],

    ["GET",'/user/{name:.+}',function($name){
        return new \Laralite\Framework\Http\Response("My name is ".$name);
    }],
];