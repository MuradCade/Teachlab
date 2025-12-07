<?php

use App\Controllers\frontend\AssignmentFrontendcontroller;
use Slim\App;
use App\Middlewares\AuthMiddleware;
use App\Controllers\Services\Redirector;


return function (App $app) {
    // Home page
    $app->get("/frontend/studentassignmentsubmission/{assignmentid}", [
        AssignmentFrontendcontroller::class,
        'index'
    ])->setName("frontend.assignment.create");

    $app->post("/frontend/studentassignmentsubmission/{assignmentid}", [
        AssignmentFrontendcontroller::class,
        'store'
    ])->setName("frontend.assignment.submit");
};
