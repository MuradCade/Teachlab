<?php

// use App\controller\Homecontroller;
// use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Http\Message\ServerRequestInterface as Request;
// use Slim\Views\Twig;

use Slim\App;
use App\Middlewares\Tenant\TenantMiddlewareHelper;
use App\Middlewares\Tenant\AuthMiddleware;
use App\Middlewares\Tenant\GuestMiddleware;
use App\Middlewares\Tenant\AuthorizationMiddleware;

use App\Controllers\Services\EmailQueueActivation;

//email queue required  things
use App\Config\Logger;

// use App\Middlewares\CsrfMiddleware;

return function (App $app) {

    /*
    * Default Routes Start Here
    */
    //get routes

    $app->get('/', [\App\Controllers\DefaultHomeController::class, 'indexHome'])
        ->setName('pages.home');
};
