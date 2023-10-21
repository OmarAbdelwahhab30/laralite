<?php

use App\controllers\SiteController;

return [
    ["GET", "/user/{id:\d+}", [SiteController::class,"index"]],
];